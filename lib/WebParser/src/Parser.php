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
class Parser
{
    public $url;
    private $crawler,
        $listName,
        $listExpression,
        $childSelectors;

    public function __construct($url)
    {
        $client = new Client();
        $this->url = $url;
        $this->crawler = $client->request('GET', $url);
    }

    public function find($name, $expression)
    {
        $this->listName = $name;
        $this->listExpression = $expression;
        $this->childSelectors = array();
        return $this;
    }

    public function prop($name, $selector, $attr = null)
    {
        array_push($this->childSelectors, new Selector($name, $selector, $attr));
        return $this;
    }

    public function select($name, $expression, $attribute = null)
    {
        try {
            $el = $this->crawler->filter($expression);
            $this->{$name} = $attribute ? $el->attr($attribute) : $el->text();
        } catch (InvalidArgumentException $e) {
        }
    }

    public function build()
    {
        $list = array();
        $childSelectors = $this->childSelectors;
        try {
            $this->crawler->filter($this->listExpression)->each(function ($node) use (&$list, $childSelectors) {
                $item = new stdClass();
                foreach ($childSelectors as $tag) {
                    // TODO: reutilizar conceito
                    $el = $node->filter($tag->getExpression());
                    $item->{$tag->getName()} = $tag->getAttr() ? $el->attr($tag->getAttr()) : $el->text();
                }

                array_push($list, $item);
            });
        } catch (InvalidArgumentException $e) {
        }

        $this->{$this->listName} = $list;
    }
}