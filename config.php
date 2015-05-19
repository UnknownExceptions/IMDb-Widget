<?php

/**
 * Configuration file
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 0.0.1
 */

define( 'IMDB_PLUGIN_URL', plugins_url() . '/' . basename( __DIR__ ) . '/' );
define( 'IMDB_PLUGIN_DIR', dirname( __FILE__ ) . '/' );

// require the needed css and javascript
function IMDb_include_scripts_and_stylesheets() {
	wp_enqueue_style( 'imdb-main-style', IMDB_PLUGIN_URL . 'css/main.css' );
}

add_action( 'wp_print_scripts', 'IMDb_include_scripts_and_stylesheets' );

// third-party libraries
require_once 'lib/htmlcompressor.php';
require_once 'vendor/autoload.php';





