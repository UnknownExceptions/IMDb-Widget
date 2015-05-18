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

/**
 * Selector
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class Selector {

	private $name, $expression, $attribute;

	public function __construct( $name, $expression, $attribute = null )
	{
		$this->setName( $name );
		$this->setExpression( $expression );
		$this->setAttr( $attribute );
	}

	public function setExpression( $tag )
	{
		$this->expression = $tag;
	}

	public function setAttr( $attr )
	{
		$this->attribute = $attr;
	}

	public function getExpression()
	{
		return $this->expression;
	}

	public function getAttr()
	{
		return $this->attribute;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName( $name )
	{
		$this->name = $name;
	}
}
