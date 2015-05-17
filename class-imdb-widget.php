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

        $info->nick = $parser->find('.header h1')->get();
        $info->avatar = $parser->find('#avatar-frame img')->attr('src')->get();
        $info->memberSince = $parser->find('.header .timestamp')->get();
        $info->bio = $parser->find('.header .biography')->get();

        $info->badges = $parser->find('.badges')->selectEach('.badge-frame')
            ->getProperty('.name')->called('name')
            ->getProperty('.value')->called('value')
            ->getProperty('.badge-icon')->attr('class')->called('image')
            ->get();

        $info->lists = $parser->find('.lists')->selectEach('.user-list')
            ->getProperty('.list-name')->called('name')
            ->getProperty('.list-meta')->attr('href')->called('link')
            ->getProperty('.list-meta')->called('meta')
            ->get();

        $info->ratings = $parser->find('.ratings')->selectEach('.item')
            ->getProperty('a')->attr('href')->called('href')
            ->getProperty('a img')->attr('src')->called('logo')
            ->getProperty('.title a')->called('title')
            ->getProperty('.sub-item .only-rating')->called('rating')
            ->get();

        return $info;
    }
}

add_action('widgets_init', create_function('', 'return register_widget("IMDb_Widget");'));
