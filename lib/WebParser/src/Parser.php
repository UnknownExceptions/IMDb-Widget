<?php

/*
 * This file is part of the WebParser package.
 *
 * (c) Henrique Dias <hacdias@gmail.com>
 * (c) Luís Soares <lsoares@gmail.com>
 *
 * Licensed under the MIT license.
 */

namespace WebParser;

use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;
use Goutte\Client as Client;

/**
 * Object
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class Parser {

	private $crawler;
	private $expression;
	private $childSelectors;

	public function __construct( $url )
	{
		$client			 = new Client();
		$this->crawler	 = $client->request( 'GET', $url );
	}

	public function selectAll( $expression )
	{
		$this->expression		 = $expression;
		$this->childSelectors	 = array(); // restart
		return $this;
	}

	public function with( $name, $selector, $attr = null )
	{
		array_push( $this->childSelectors, new Selector( $name, $selector, $attr ) );
		return $this;
	}

	public function select( $expression, $attribute = null, Crawler $crawler = null )
	{
		try {
			$context = isset( $crawler ) ? $crawler : $this->crawler;
			$el		 = $context->filter( $expression );
			return $attribute ? $el->attr( $attribute ) : $el->text();
		} catch ( InvalidArgumentException $e ) {
			return null;
		}
	}

	public function build()
	{
		$lists			 = array();
		$childSelectors	 = $this->childSelectors;
		try {
			$this->crawler->filter( $this->expression )->each( function ($node) use (&$lists, $childSelectors) {
				$item = new stdClass();

				foreach ( $childSelectors as $tag ) {
					$item->{$tag->getName()} = $this->select( $tag->getExpression(), $tag->getAttr(), $node );
				}

				array_push( $lists, $item );
			} );
		} catch ( InvalidArgumentException $e ) {
			// return empty
		}
		return $lists;
	}

}
