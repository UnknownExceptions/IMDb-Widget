<?php

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
        "username"
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
        $instance = serialize($newInstance);
        return $instance;
    }

    public function widget($args, $config)
    {
        extract($args, EXTR_SKIP);
        $config = !empty($config) ? unserialize($config) : array();

        /* $config['theme'] = isset($config['theme']) ? $config['theme'] : null;

         switch ($config['theme']) {
             case 'dark':
                 wp_enqueue_style('dark-style', HACKERRANK_PLUGIN_URL . 'css/dark.css');
                 break;
             case 'balanced':
                 wp_enqueue_style('balanced-style', HACKERRANK_PLUGIN_URL . 'css/balanced.css');
                 break;
             case 'light':
             default:
                 wp_enqueue_style('light-style', HACKERRANK_PLUGIN_URL . 'css/light.css');
         } */

        ob_start("HTMLCompressor");
        require 'pieces/widget.php';
        ob_end_flush();
    }
}

add_action('widgets_init', create_function('', 'return register_widget("IMDBWidget");'));
