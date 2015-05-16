<?php

namespace IMDbParser;

use Goutte\Client as WebScrapper;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{

    public function __construct( $userId )
    {
        $this->userId = $userId;
    }

    public function getInfo()
    {
        $info                = new stdClass();
        $info->userId        = $this->userId;
		$info->baseUrl       = 'http://www.imdb.com';
        $info->profileUrl    = $info->baseUrl . '/user/' . $this->userId . '/';
		$info->ratingsUrl    = $info->profileUrl . 'ratings';
        $info->listsUrl      = $info->profileUrl . 'lists';
        $info->boardsUrl     = $info->profileUrl . 'boards';
        $info->watchlistUrl  = $info->profileUrl . 'watchlist';
        $info->checkinsUrl   = $info->profileUrl . 'checkins';
        $info->commentsUrl   = $info->profileUrl . 'comments-index';
        $info->pollsUrl      = $info->profileUrl . '#pollResponses';
        $info->ratingsUrlRss = str_replace( 'www', 'rss', $info->ratingsUrl );

        $client  = new WebScrapper();
        $crawler = $client->request( 'GET', $info->profileUrl );

        $info->nick        = $this->parseElement( $crawler, ".header h1" );
        $info->avatar      = $this->parseElement( $crawler, '#avatar-frame img', 'src' );
        $info->memberSince = $this->parseElement( $crawler, ".header .timestamp" );
        $info->bio         = $this->parseElement( $crawler, ".header .biography" );

        $badgesOptions = array(
            'name'  => array( '.name' ),
            'value' => array( '.value' ),
            'image' => array( '.badge-icon', 'class' )
        );

        $info->badges = $this->parseList( $crawler, '.badges .badge-frame', $badgesOptions );

        $listsOptions = array(
            'name' => array( '.list-name' ),
            'link' => array( '.list-name', 'href' ),
            'meta' => array( '.list-meta' )
        );

        $info->lists = $this->parseList( $crawler, '.lists .user-list', $listsOptions );
		
		$ratingsOptions = array(
			'href' => array( 'a', 'href'),
            'logo' => array( 'img', 'src' ),
            'title' => array( 'div a' )
		);
		$info->ratings = $this->parseList( $crawler, '.ratings .item', $ratingsOptions );
        
        return $info;
    }

    private function parseElement( Crawler $crawler, $what, $attr = null )
    {
        try {
			$el = $crawler->filter( $what );
			return isset ($attr) ? $el->attr( $attr ) : $el->text( $attr );
        } catch ( InvalidArgumentException $e ) {
            return null;
        }
    }

    private function parseList( Crawler $crawler, $tag, $sub_tags )
    {
        $lists = array();
        try {
            $crawler->filter( $tag )->each( function ( $node ) use ( &$lists, $sub_tags ) {
                $newItem = new stdClass();
                foreach ($sub_tags as $key => $value) {
                    $newItem->{$key} = $this->parseElement( $node, $value[0], isset( $value[1] ) ? $value[1] : null );
                }
                array_push( $lists, $newItem );
            } );
        } catch ( InvalidArgumentException $e ) {
            // probably the user don't have lists
        }
        return $lists;
    }
}