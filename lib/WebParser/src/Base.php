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

use Goutte\Client as BaseClient;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Base
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
abstract class Base
{
    private $crawler;

    /**
     * Constructor
     *
     * @param Crawler $crawler
     */
    public function __construct($crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * List parser
     *
     * @param Selector $tag
     * @param Selector ...$subTags
     * @return array
     */
    protected function parseList(Selector $tag, Selector ...$subTags)
    {
        $lists = array();
        $tag = $tag->getTag();

        try {
            $this->crawler->filter($tag)->each(function ($node) use (&$lists, $subTags) {
                $item = new stdClass();

                foreach ($subTags as $tag) {
                    if (!$tag instanceof Selector) {
                        continue;
                    }

                    $item->{$tag->getName()} = $this->parseElement($tag, $node);
                }

                array_push($lists, $item);
            });

        } catch (InvalidArgumentException $e) {
            // return empty
        }
        return $lists;
    }

    /**
     * Element parser
     *
     * @param Selector $selector
     * @param Crawler $crawler
     * @return null|string
     */
    protected function parseElement(Selector $selector, Crawler $crawler = null)
    {
        try {
            if (isset($crawler)) {
                $el = $crawler->filter($selector->getTag());
            } else {
                $el = $this->crawler->filter($selector->getTag());
            }

            return ($selector->getAttr()) ? $el->attr($selector->getAttr()) : $el->text();
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

}