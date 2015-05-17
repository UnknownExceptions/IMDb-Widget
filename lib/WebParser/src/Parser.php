<?php

namespace WebParser;

use Goutte\Client as BaseClient;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function __construct($url)
    {
        $this->url = $url;
        $this->client = new BaseClient();
        $this->crawler = $this->client->request('GET', $this->url);
    }

    public function parse(Selector ...$selectors)
    {
        if (count($selectors) === 1) {
            return $this->parseElement($selectors[0]);
        } else {
            return $this->parseList(array_shift($selectors), ...$selectors);
        }
    }

    private function parseElement(Selector $selector, Crawler $crawler = null)
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

    private function parseList(Selector $tag, Selector ...$subTags)
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

}