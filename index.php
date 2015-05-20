<?php
/**
 * Plugin Name: IMDb Widget
 * Description: This is a plugin that shows your IMDd profile with a simple widget.
 * Version: 0.0.1
 * Author: Henrique Dias, Luís Soares
 * Author URI: http://henriquedias.com, http://luissoares.com
 * Network: true
 * License: GPL2 or later
 */


define( 'IMDB_PLUGIN_URL', plugins_url() . '/' . basename( __DIR__ ) . '/' );
define( 'IMDB_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

// require the needed css and javascript
function IMDb_include_scripts_and_stylesheets() {
	wp_enqueue_style( 'imdb-main-style', IMDB_PLUGIN_URL . 'css/main.css' );
}

add_action( 'wp_print_scripts', 'IMDb_include_scripts_and_stylesheets' );

// third-party libraries
require_once ('lib/htmlcompressor.php');
require_once ('vendor/autoload.php');

// THE widget
require_once ('class-imdb-widget.php');
