<?php

use Goutte\Client;

/**
 * Widget Class
 *
 * @package HackerRank Profile Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */
class IMDBWidget extends WP_Widget
{

    protected $options = array(
        "title",
        "userId"
    );

    public function __construct()
    {
        parent::__construct(
            'IMDBWidget', 'IMDB :: Profile',
            array('description' => 'A widget to show a small version of your IMDB profile.')
        );
    }

    public function form($config)
    {
        $config = !empty($config) ? unserialize($config) : array();

        foreach ($this->options as $option) {
            ${$option} = isset($config[$option]) ? $config[$option] : null;
        }

        ob_start("HTMLCompressor");
        require 'pieces/form.php';
        ob_end_flush();
    }

    public function update($newInstance, $oldInstance)
    {
        return serialize($newInstance);
    }

    public function widget($args, $config)
    {
        extract($args, EXTR_SKIP);
        $config = !empty($config) ? unserialize($config) : array();

        ob_start("HTMLCompressor");

        if (!isset($config['userId'])) { // TODO: devia chamar-se userId. para nao confundir com o username
            echo 'You need to first configure the plugin :)';
        } else {
            $info = $this->fetchIMDbUserInfo($config['userId']);
            require 'pieces/widget.php';
        }

        ob_end_flush();
    }

    /**
     * @param $userId
     * @return stdClass
     */
    protected function fetchIMDbUserInfo($userId)
    {
        $info = new stdClass();
        $info->profileUrl = 'http://www.imdb.com/user/' . $userId . '/';
        $info->ratingsUrl = $info->profileUrl . 'ratings';
        $info->listsUrl = $info->profileUrl . 'lists';
        $info->boardsUrl = $info->profileUrl . 'boards';
        $info->watchlistUrl = $info->profileUrl . 'watchlist';
        $info->checkinsUrl = $info->profileUrl . 'checkins';
        $info->commentsUrl = $info->profileUrl . 'comments-index';
        $info->pollsUrl = $info->profileUrl . '#pollResponses';
        $info->ratingsUrlRss = str_replace('www', 'rss', $info->ratingsUrl);

        $client = new Client();
        /* TODO: evitar criar sempre um novo cliente. reutilizar cliente.
        H: como assim? Não é usado apenas uma vez? A func fetchIMDbUserInfo só é chamada 1 vez, logo o cliente só é
        criado uma vez. Ou...? */
        $crawler = $client->request('GET', $info->profileUrl);
        $info->userId = $userId;
        $info->nick = $this->parseIMDbInfo($crawler, ".header h1");
        $info->avatar = $this->parseIMDbInfo($crawler, '#avatar-frame img', 'src');
        $info->memberSince = $this->parseIMDbInfo($crawler, ".header .timestamp");
        $info->bio = $this->parseIMDbInfo($crawler, ".header .biography");
        $info->badges = $this->parseIMDbBadges($userId);

        return $info;
    }

    /**
     * @param $crawler
     * @param $what
     * @param null $attr
     * @return null
     */
    protected function parseIMDbInfo($crawler, $what, $attr = null)
    {
        try {
            if (isset($attr)) {
                return $crawler->filter($what)->attr($attr);
            }

            return $crawler->filter($what)->text();
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    //TODO: complete this
    protected function parseIMDbBadges($crawler)
    {
        $badges = array();

        return $badges;
    }

    protected function parseIMDbUserLists($crawler)
    {
        //TODO: ver bem isto;
        $this->lists = array();
        $crawler->filter('.lists .user-list')->each(function ($node) {
            if ($this->parseIMDbInfo($node, '.list-name')) {
                $this->lists[$this->parseIMDbInfo($node, '.list-name')] = $node->filter('.list-meta')->text();
            }
        });

        return $this->lists;
    }

}

add_action('widgets_init', create_function('', 'return register_widget("IMDBWidget");'));
