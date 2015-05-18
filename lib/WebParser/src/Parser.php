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

use Goutte\Client as Client;
use InvalidArgumentException;
use stdClass;

/**
 * Parser
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class Parser {

	public $url;
	private $crawler,
	$listName,
	$listExpression,
	$childSelectors;

	public function __construct( $url )
	{
		$client			 = new Client();
		$this->url		 = $url;
		$this->crawler	 = $client->request( 'GET', $url );
	}

	public function select( $name, $expression, $attribute = null )
	{
		$this->{$name} = $this->smartSelect( $this->crawler, new Selector( $name, $expression, $attribute ) );
	}

	public function prepare( $name, $expression )
	{
		$this->listName			 = $name;
		$this->listExpression	 = $expression;
		$this->childSelectors	 = array();
		return $this;
	}

	public function with( $name, $expression, $attribute = null )
	{
		array_push( $this->childSelectors, new Selector( $name, $expression, $attribute ) );
		return $this;
	}

	private function smartSelect( $context, Selector $selector )
	{
		try {
			$el = $context->filter( $selector->getExpression() );
			return $selector->getAttr() ? $el->attr( $selector->getAttr() ) : $el->text();
		} catch ( InvalidArgumentException $e ) {
			return null;
		}
	}

	public function finish()
	{
		$subSelections	 = array();
		$childSelectors	 = $this->childSelectors;
		try {
			$this->crawler->filter( $this->listExpression )->each( function ($node) use (&$subSelections, $childSelectors) {
				$item = new stdClass();
				foreach ( $childSelectors as $childSelector ) {
					$item->{$childSelector->getName()}	 = $this->smartSelect( $node, $childSelector );
				}
				array_push( $subSelections, $item );
			} );
		} catch ( InvalidArgumentException $e ) {
			
		}

		$this->{$this->listName} = $subSelections; // keep
	}

}
