<?php
/**
 * Configuration file
 * 
 * @package HackerRank Profile Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */

// base constants
define('IMDB_PLUGIN_URL', plugins_url() . '/' . basename(__DIR__) . '/');
define('IMDB_PLUGIN_DIR', dirname(__FILE__) . '/');

// require the needed libraries
require_once IMDB_PLUGIN_DIR . 'lib/htmlcompressor.php';
require_once IMDB_PLUGIN_DIR . 'lib/goutte.phar';

// require the needed css and javascript
function IMDBPlugin_addJavascriptAndCss()
{
    wp_enqueue_style('main-style', IMDB_PLUGIN_URL . 'css/main.css');
    wp_register_script('main-js', IMDB_PLUGIN_URL . 'js/app.js');
}

add_action('wp_print_scripts', 'IMDBPlugin_addJavascriptAndCss');



