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
        $info = new stdClass();
        $info->baseUrl = 'http://www.imdb.com/';
        $info->profileUrl = $info->baseUrl . 'user/' . $userId;
        $info->ratingsRssUrl = str_replace('www', 'rss', $info->profileUrl);

        $urlsToAdd = array(
            'ratings' => 'ratings',
            'lists' => 'boards',
            'watchlist' => 'watchlist',
            'checkins' => 'checkins',
            'comments' => 'comments-index',
            'polls' => '#pollResponses'
        );

        foreach ($urlsToAdd as $name => $url) {
            $info->{$name . 'Url'} = $info->baseUrl . $url;
        }

        $parser = new Parser($info->profileUrl);

        $info->nick = $parser->parse(new Selector('nick', '.header h1'));
        $info->avatar = $parser->parse(new Selector('avatar', '#avatar-frame img', 'src'));
        $info->memberSince = $parser->parse(new Selector('memberSince', '.header .timestamp'));
        $info->bio = $parser->parse(new Selector('bio', '.header .biography'));
        $info->badges = $parser->parse(new Selector('badges', '.badges .badge-frame'),
            new Selector('name', '.name'),
            new Selector('value', '.value'),
            new Selector('image', '.badge-icon', 'class'));
        $info->lists = $parser->parse(new Selector('lists', '.lists .user-list'),
            new Selector('name', '.list-name'),
            new Selector('link', '.list-meta', 'href'),
            new Selector('meta', '.list-meta'));
        $info->ratings = $parser->parse(new Selector('ratings', '.ratings .item'),
            new Selector('href', 'a', 'href'),
			new Selector('logo', 'a img', 'src'),
            new Selector('title', '.title a'),
		    new Selector('rating', '.sub-item .only-rating'));

        return $info;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("IMDb_Widget");'));
