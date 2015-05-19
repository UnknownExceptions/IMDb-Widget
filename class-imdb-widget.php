<?php

use WebParser\Parser;

require_once __DIR__ . '/lib/htmlcompressor.php';
require_once __DIR__ . '/lib/WebParser/index.php';

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
                'IMDbWidget', 'IMDb', array('description' => 'A widget to show a small version of your IMDb profile.')
        );
    }

    public function form($config)
    {
        $config = !empty($config) ? unserialize($config) : array();

        foreach ($this->options as $option)
        {
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

        if (!isset($config['userId']))
        {
            echo 'You need to first configure the plugin :)';
        } else
        {
            $info = $this->get_info($config['userId']);
            require 'pieces/widget.php';
        }
        ob_end_flush();
    }

    private function get_info($userId)
    {
        $info = new Parser('http://www.imdb.com/' . 'user/' . $userId . '/');
        $info->baseUrl = 'http://www.imdb.com';

        foreach (array('ratings', 'boards', 'watchlist', 'checkins', 'comments-index', '#pollResponses') as $relativeUrl)
        {
            $cleanId = preg_replace('/[^A-Za-z]/', '', $relativeUrl);
            $info->{$cleanId . 'Url'} = $info->url . $relativeUrl;
        }

        $info->select('nick', '.header h1');
        $info->select('avatar', '#avatar-frame img', 'src');
        $info->select('memberSince', '.header .timestamp');
        $info->select('bio', '.header .biography');
        $info->select('ratingsCount', '.see-more a');

        $info->selectList('ratings', '.ratings .item')
                ->with('href', 'a', 'href')
                ->with('logo', 'a img', 'src')
                ->with('title', '.title a')
                ->with('rating', '.sub-item .only-rating')
                ->save();

        $info->selectList('badges', '.badges .badge-frame')
                ->with('title', '.name')
                ->with('value', '.value')
                ->with('logo', '.badge-icon') // TODO http://goo.gl/hHV7gW    $('style')[1] mas agora só com parse CSS
                ->save();

        $info->selectList('watchlist', '.watchlist .item')
                ->with('title', '.sub-item a')
                ->with('link', 'a', 'href')
                ->with('logo', 'a img', 'src')
                ->save();

        $info->selectList('lists', '.lists .user-list')
                ->with('title', '.list-name')
                ->with('link', '.list-meta', 'href')
                ->with('meta', '.list-meta')
                ->save();

        return $info;
    }

}

add_action('widgets_init', create_function('', 'return register_widget("IMDb_Widget");'));
