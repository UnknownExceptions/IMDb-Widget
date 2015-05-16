<?php
/**
 * Configuration file
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */

ini_set('display_errors', 1);  error_reporting(E_ALL);

define( 'IMDB_PLUGIN_URL', plugins_url() . '/' . basename( __DIR__ ) . '/' );
define( 'IMDB_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

// third-party libraries
require_once IMDB_PLUGIN_DIR . 'lib/htmlcompressor.php';
require_once IMDB_PLUGIN_DIR . 'lib/goutte.phar';

// require the needed css and javascript
function IMDb_plugin_add_javascript_and_css() {
	wp_enqueue_style( 'main-style', IMDB_PLUGIN_URL . 'css/main.css' );
	wp_register_script( 'main-js', IMDB_PLUGIN_URL . 'js/app.js' );
}

add_action( 'wp_print_scripts', 'IMDb_plugin_add_javascript_and_css' );



