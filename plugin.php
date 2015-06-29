<?php

/**
 * Plugin Name: IMDb Profile Widget
 * Description: This is a plugin that shows your IMDd profile with a simple widget.
 * Version: 1.0.8
 * Author: Henrique Dias and Luís Soares (imdb)
 * Author URI: https://github.com/imdb
 * Network: true
 * License: GPL2 or later
 *
 * IMDb Widget for WordPress
 *
 *     Copyright (C) 2015 Henrique Dias     <hacdias@gmail.com>
 *     Copyright (C) 2015 Luís Soares       <lsoares@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

// third-party libraries
require_once( 'lib/htmlcompressor.php' );
require_once( 'vendor/autoload.php' );

use SmartScraper\Parser;

// prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// check the current PHP version
if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
	exit( sprintf( 'Foo requires PHP 5.6.0 or higher. You’re still on %s.', PHP_VERSION ) );
}

class IMDb_Widget extends WP_Widget {

	protected $widget_slug = 'imdb-widget';
	protected $options = array(
		"title",
		"userId"
	);
	protected $config;
	protected $optionsShow = array(
		'bio',
		'member since',
		'picture',
		'badges',
		'watchlist',
		'lists',
		'ratings',
		'reviews',
		'boards'
	);

	public function __construct() {
		parent::__construct(
			$this->get_widget_slug(), __( 'IMDb Profile ', $this->get_widget_slug() ), array(
				'classname'   => $this->get_widget_slug() . '-class',
				'description' => __( 'A widget to show a small version of your IMDb profile.', $this->get_widget_slug() )
			)
		);

		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );
	}

	public function get_widget_slug() {
		return $this->widget_slug;
	}

	public function form( $config ) {
		$config = ! empty( $config ) ? unserialize( $config ) : array();

		foreach ( $config as $key => $value ) { // recover options
			${$key} = esc_attr( $value );
		}

		ob_start( "imdb_HTMLCompressor" );
		require 'views/options.php';
		ob_end_flush();
	}

	public function update( $new_instance, $old_instance ) {
		return serialize( $new_instance );
	}

	public function widget( $args, $config ) {
		extract( $args, EXTR_SKIP );
		$config = ! empty( $config ) ? unserialize( $config ) : array();

		ob_start( "imdb_HTMLCompressor" );

		if ( ! isset( $config['userId'] ) ) {
			echo 'You need to first configure the plugin :)';
		} else {
			$info = $this->get_info( $config['userId'] );
			require 'views/widget.php';
		}

		ob_end_flush();
	}

	private function get_info( $userId ) {
		$info          = new Parser( 'http://www.imdb.com/' . 'user/' . $userId . '/' );
		$info->baseUrl = 'http://www.imdb.com';

		foreach (
			array(
				'ratings',
				'boards',
				'lists',
				'watchlist',
				'checkins',
				'boards/sendpm',
				'comments-index',
				'#pollResponses'
			) as $relativeUrl
		) {
			$cleanId                  = preg_replace( '/[^A-Za-z]/', '', $relativeUrl );
			$info->{$cleanId . 'Url'} = $info->url . $relativeUrl;
		}

		$info->saveText( 'nick', '.header h1' );
		$info->saveText( 'avatar', '#avatar-frame img', 'src' );
		$info->saveText( 'memberSince', '.header .timestamp' );
		$info->saveText( 'bio', '.header .biography' );
		$info->saveText( 'ratingsCount', '.see-more a' );
		$info->saveHtml( 'ratingsDistribution', '.overall .histogram-horizontal' );
		$info->saveHtml( 'ratingsByYear', '.byYear .histogram-horizontal' );
		$info->saveHtml( 'ratingsByYearLegend', '.byYear .legend' );
		$info->saveHtml( 'ratingsTopRatedGenres', '.histogram-vertical', 0 );
		$info->saveHtml( 'ratingsTopRatedYears', '.histogram-vertical', 1 );

		$info->at( '.ratings .item' )
		     ->with( 'link', 'a', 'href' )
		     ->with( 'logo', 'a img', 'src' )
		     ->with( 'title', '.title a' )
		     ->with( 'rating', '.sub-item .only-rating' )
		     ->saveList( 'ratings' );

		$info->at( '.badges .badge-frame' )
		     ->with( 'title', '.name' )
		     ->with( 'value', '.value' )
		     ->saveList( 'badges' );

		$info->at( '.watchlist .item' )
		     ->with( 'title', '.sub-item a' )
		     ->with( 'link', 'a', 'href' )
		     ->with( 'logo', 'a img', 'src' )
		     ->saveList( 'watchlist' );

		$info->at( '.lists .user-list' )
		     ->with( 'title', '.list-name' )
		     ->with( 'link', '.list-meta', 'href' )
		     ->with( 'meta', '.list-meta' )
		     ->saveList( 'lists' );

		$info->at( '.user-lists .user-list' )
		     ->with( 'logo', 'img', 'src' )
		     ->with( 'title', '.list-name' )
		     ->with( 'link', 'a', 'href' )
		     ->with( 'description', '.list-meta' )
		     ->saveList( 'userLists' );

		$info->at( '.reviews .item' )
		     ->with( 'movieLogo', '.image img', 'src' )
		     ->with( 'movieLink', '.image a', 'href' )
		     ->with( 'movieTitle', 'h3 a' )
		     ->with( 'movieYear', 'h3 span' )
		     ->with( 'title', 'h4' )
		     ->with( 'meta', '.details' )
		     ->with( 'text', 'p' )
		     ->saveList( 'reviews' );

		$info->at( '.boards-comments .row' )
		     ->with( 'boardLink', '.board a', 'href' )
		     ->with( 'boardTitle', '.board a' )
		     ->with( 'commentTitle', '.title a' )
		     ->with( 'commentLink', '.title a', 'href' )
		     ->with( 'when', '.when' )
		     ->saveList( 'boards' );

		return $info;
	}

	public function isChecked( $conf, $name ) {
		return isset( $conf[ $name ] ) && $conf[ $name ] == 'on';
	}

	public function register_widget_styles() {
        wp_enqueue_style( $this->get_widget_slug() . '-widget-styles', plugins_url( 'css/widget.css', __FILE__ ) );
	}

	public function register_widget_scripts() {
		wp_enqueue_script( $this->get_widget_slug() . '-script', plugins_url( 'js/widget.js', __FILE__ ), array( 'jquery' ), null, true );
	}

	private function serveImage( $imageUrl ) {
		return plugins_url( 'pic.php', __FILE__ ) . '?url=' . $imageUrl;
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("IMDb_Widget");' ) );
