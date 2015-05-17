<?php

use WebParser\Parser;
use WebParser\Selector;

/**
 * Widget Class
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */
class IMDb_Widget extends WP_Widget
{

    private $options = array(
        "title",
        "userId"
    );

    public function __construct()
    {
        parent::__construct(
            'IMDbWidget', 'IMDb',
            array('description' => 'A widget to show a small version of your IMDb profile.')
        );
    }

    public function form($config)
    {
        $config = !empty($config) ? unserialize($config) : array();

        foreach ($this->options as $option) {
            ${$option} = isset($config[$option]) ? $config[$option] : null;
        }

        ob_start("HTMLCompressor");
        require 'pieces/options.php';
        ob_end_flush();
    }

    public function update($new_instance, $old_instance)
    {
        return serialize($new_instance);
    }

    public function widget($args, $config)
    {
        extract($args, EXTR_SKIP);
        $config = !empty($config) ? unserialize($config) : array();

        ob_start("HTMLCompressor");

        if (!isset($config['userId'])) {
            echo 'You need to first configure the plugin :)';
        } else {
            //$parser = new Parser($config['userId']);
            //$info = $parser->getInfo();
            $info = $this->getInfo($config['userId']);
            require 'pieces/widget.php';
        }
        ob_end_flush();
    }

    private function getInfo($userId)
    {
        $baseUrl = 'http://www.imdb.com';

        $parser = new Parser($baseUrl);

        $parser->setUrl('profile', 'user/' . $userId);
        $parser->setUrl('ratingsRss', str_replace('www', 'rss', $parser->getUrl('profile')), null);

        $urlsToAdd = array(
            'ratings' => 'ratings',
            'lists' => 'boards',
            'watchlist' => 'watchlist',
            'checkins' => 'checkins',
            'comments' => 'comments-index',
            'polls' => '#pollResponses'
        );

        foreach ($urlsToAdd as $name => $url) {
            $parser->setUrl($name, $url, 'profile');
        }

        $parser->makeCrawler('profile', 'GET', $parser->getUrl('profile'));

        $parser->fetchInformation('profile',
            new Selector('nick', '.header h1'));

        $parser->fetchInformation('profile',
            new Selector('avatar', '#avatar-frame img', 'src'));

        $parser->fetchInformation('profile',
            new Selector('memberSince', '.header .timestamp'));

        $parser->fetchInformation('profile',
            new Selector('bio', '.header .biography'));

        $parser->fetchInformation('profile',
            new Selector('badges', '.badges .badge-frame'),
            new Selector('name', '.name'),
            new Selector('value', '.value'),
            new Selector('image', '.badge-icon', 'class'));

        $parser->fetchInformation('profile',
            new Selector('lists', '.lists .user-list'),
            new Selector('name', '.list-name'),
            new Selector('link', '.list-meta', 'href'),
            new Selector('meta', '.list-meta'));

        $parser->fetchInformation('profile',
            new Selector('ratings', '.ratings .item'),
            new Selector('href', 'a', 'href'),
            new Selector('title', 'div a'));

        return $parser->getInfo();
    }
}

add_action('widgets_init', create_function('', 'return register_widget("IMDb_Widget");'));
