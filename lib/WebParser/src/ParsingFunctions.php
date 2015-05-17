<?php

namespace WebParser;

use Goutte\Client as BaseClient;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

abstract class ParsingFunctions
{

    private $crawler;

    public function __construct($crawler)
    {
        $this->crawler = $crawler;
    }

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