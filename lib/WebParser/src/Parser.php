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
use Goutte\Client as Client;

/**
 * Object
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class Parser extends stdClass {

	private $crawler;
	private $expression;
	private $childSelectors;

	public function __construct( $url )
	{
		$client			 = new Client();
		$this->crawler	 = $client->request( 'GET', $url );
	}

	public function find( $expression )
	{
		$this->expression		 = $expression;
		$this->childSelectors	 = array(); // restart
		return $this;
	}

	public function prop( $name, $selector, $attr = null )
	{
		array_push( $this->childSelectors, new Selector( $name, $selector, $attr ) );
		return $this;
	}

	public function select( $name, $expression, $attribute = null )
	{
		try {
			$el				 = $this->crawler->filter( $expression );
			$this->{$name}	 = $attribute ? $el->attr( $attribute ) : $el->text();
		} catch ( InvalidArgumentException $e ) {
			
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
					// TODO: reutilizar conceito.
					$el						 = $node->find( $tag->getExpression() );
					$item->{$tag->getName()} = $tag->getAttr() ? $el->attr( $tag->getAttr() ) : $el->text();
				}

				array_push( $lists, $item );
			} );
		} catch ( InvalidArgumentException $e ) {
			// return empty
		}
		return $lists;
	}

}
