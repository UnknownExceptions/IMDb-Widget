<?php

use Goutte\Client as WebScrapper;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Widget Class
 *
 * @package IMDb Widget
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 * @version 1.0.0
 */
class IMDb_Widget extends WP_Widget {

	protected $options = array(
		"title",
		"userId"
	);

	public function __construct() {
		parent::__construct(
		'IMDbWidget', 'IMDb', array( 'description' => 'A widget to show a small version of your IMDb profile.' )
		);
	}

	public function form( $config ) {
		$config = !empty( $config ) ? unserialize( $config ) : array();

		foreach ( $this->options as $option ) {
			${$option} = isset( $config[ $option ] ) ? $config[ $option ] : null;
		}

		ob_start( "HTMLCompressor" );
		require 'pieces/options.php';
		ob_end_flush();
	}

	public function update( $newInstance, $oldInstance ) {
		return serialize( $newInstance );
	}

	public function widget( $args, $config ) {
		extract( $args, EXTR_SKIP );
		$config = !empty( $config ) ? unserialize( $config ) : array();

		ob_start( "HTMLCompressor" );

		if ( !isset( $config[ 'userId' ] ) ) {
			echo 'You need to first configure the plugin :)';
		} else {
			$info = $this->fetch_imdb_user_info( $config[ 'userId' ] );
			require 'pieces/widget.php';
		}

		ob_end_flush();
	}

	/**
	 * @param $userId
	 *
	 * @return stdClass
	 */
	protected function fetch_imdb_user_info( $userId ) {
		$info				 = new stdClass();
		$info->profileUrl	 = 'http://www.imdb.com/user/' . $userId . '/';
		$info->ratingsUrl	 = $info->profileUrl . 'ratings';
		$info->listsUrl		 = $info->profileUrl . 'lists';
		$info->boardsUrl	 = $info->profileUrl . 'boards';
		$info->watchlistUrl	 = $info->profileUrl . 'watchlist';
		$info->checkinsUrl	 = $info->profileUrl . 'checkins';
		$info->commentsUrl	 = $info->profileUrl . 'comments-index';
		$info->pollsUrl		 = $info->profileUrl . '#pollResponses';
		$info->ratingsUrlRss = str_replace( 'www', 'rss', $info->ratingsUrl );

		$client				 = new WebScrapper();
		$crawler			 = $client->request( 'GET', $info->profileUrl );
		$info->userId		 = $userId;
		$info->nick			 = $this->parse_imdb_info( $crawler, ".header h1" );
		$info->avatar		 = $this->parse_imdb_info( $crawler, '#avatar-frame img', 'src' );
		$info->memberSince	 = $this->parse_imdb_info( $crawler, ".header .timestamp" );
		$info->bio			 = $this->parse_imdb_info( $crawler, ".header .biography" );
		$info->recentRatings = $this->parse_imdb_ratings( $crawler );
		$info->badges		 = $this->parse_imdb_badges( $crawler );
		$info->lists		 = $this->parse_imdb_lists( $crawler );

		return $info;
	}

	protected function parse_imdb_ratings( Crawler $crawler ) {
//		.ratings .item
		return array();
	}

	//TODO: complete this
	protected function parse_imdb_badges( Crawler $crawler ) {
		$badges = array();

		return $badges;
	}

	/**
	 * @param $crawler
	 *
	 * @return mixed
	 */
	protected function parse_imdb_lists( Crawler $crawler ) {
		try {
			$crawler->filter( '.lists .user-list' )->each( function ( $node ) {
				$name	 = $this->parse_imdb_info( $node, '.list-name' );
				$link	 = $this->parse_imdb_info( $node, '.list-name', 'href' );
				$meta	 = $this->parse_imdb_info( $node, '.list-meta' );
				$this->process_imdb_lists( $name, $link, $meta );
			} );
		} catch ( InvalidArgumentException $e ) {
			// what should I do here?
			// devia retornar 1 lista vazia!
		}

		if ( isset( $this->lists ) ) {
			return $this->lists;
		}

		return array();
	}

	/**
	 * @param $name
	 * @param $link
	 * @param $meta
	 */
	private function process_imdb_lists( $name, $link, $meta ) {
		if ( !isset( $this->counter ) ) {
			$this->counter = 0;
		}

		if ( !isset( $this->lists ) ) {
			$this->lists = array();
		}

		$this->lists[ $this->counter ] = array(
			'name'	 => $name,
			'link'	 => $link,
			'meta'	 => $meta
		);

		$this->counter ++;

		return;
	}

	/**
	 * @param $crawler
	 * @param $what
	 * @param null $attr
	 *
	 * @return null
	 */
	protected function parse_imdb_info( Crawler $crawler, $what, $attr = null ) {
		try {
			if ( isset( $attr ) ) {
				return $crawler->filter( $what )->attr( $attr );
			}

			return $crawler->filter( $what )->text();
		} catch ( InvalidArgumentException $e ) {
			return null;
		}
	}

}

add_action( 'widgets_init', create_function( '', 'return register_widget("IMDb_Widget");' ) );
