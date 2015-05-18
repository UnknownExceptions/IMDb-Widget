<?php

use WebParser\Parser;

/**
 * Widget Class
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */
class IMDb_Widget extends WP_Widget {

	private $options = array(
		"title",
		"userId"
	);

	public function __construct()
	{
		parent::__construct(
		'IMDbWidget', 'IMDb', array( 'description' => 'A widget to show a small version of your IMDb profile.' )
		);
	}

	public function form( $config )
	{
		$config = !empty( $config ) ? unserialize( $config ) : array();

		foreach ( $this->options as $option ) {
			${$option} = isset( $config[ $option ] ) ? $config[ $option ] : null;
		}

		ob_start( "HTMLCompressor" );
		require 'pieces/options.php';
		ob_end_flush();
	}

	public function update( $new_instance, $old_instance )
	{
		return serialize( $new_instance );
	}

	public function widget( $args, $config )
	{
		extract( $args, EXTR_SKIP );
		$config = !empty( $config ) ? unserialize( $config ) : array();

		ob_start( "HTMLCompressor" );

		if ( !isset( $config[ 'userId' ] ) ) {
			echo 'You need to first configure the plugin :)';
		} else {
			$info = $this->getInfo( $config[ 'userId' ] );
			require 'pieces/widget.php';
		}
		ob_end_flush();
	}

	private function getInfo( $userId )
	{
		$info				 = new stdClass();
		$info->baseUrl		 = 'http://www.imdb.com/';
		$info->profileUrl	 = $info->baseUrl . 'user/' . $userId;
		$info->ratingsRssUrl = str_replace( 'www', 'rss', $info->profileUrl );

		$urlsToAdd = array(
			'ratings', 'boards', 'watchlist', 'checkins', 'comments ndex', '#pollResponses'
		);

		foreach ( $urlsToAdd as $url ) {
			$cleanId					 = preg_replace( '/[^A-Za-z]/', '', $url );
			$info->{$cleanId . 'Url'}	 = $info->baseUrl . $url;
		}

		$parser = new Parser( $info->profileUrl );

		$info->nick			 = $parser->select( '.header h1' );
		$info->avatar		 = $parser->select( '#avatar-frame img', 'src' );
		$info->memberSince	 = $parser->select( '.header .timestamp' );
		$info->bio			 = $parser->select( '.header .biography' );

		$info->badges = $parser->selectAll( '.badges .badge-frame' )
		->with( 'name', '.name' )
		->with( 'value', '.value' )
		->with( 'image', '.badge-icon', 'class' )
		->build();

		$info->lists = $parser->selectAll( '.lists .user-list' )
		->with( 'name', '.list-name' )
		->with( 'link', '.list-meta', 'href' )
		->with( 'meta', '.list-meta' )
		->build();

		$info->ratings = $parser->selectAll( '.ratings .item' )
		->with( 'href', 'a', 'href' )
		->with( 'logo', 'a img', 'src' )
		->with( 'title', '.title a' )
		->with( 'rating', '.sub-item .only-rating' )
		->build();

		return $info;
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("IMDb_Widget");' ) );
