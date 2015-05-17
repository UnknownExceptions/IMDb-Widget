<?php

/**
 *
 */
namespace WebParser;

use Goutte\Client as WebScrapper;
use InvalidArgumentException;
use stdClass as Object;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function __construct($baseUrl)
    {
        $this->info = new Object();
        $this->info->baseUrl = rtrim($baseUrl, '/') . '/';
        $this->client = new WebScrapper();
    }

    public function setUrl($name, $url, $basedOnUrl = 'base')
    {
        if ($basedOnUrl) {
            $this->info->{$name . 'Url'} = $this->info->{$basedOnUrl . 'Url'} . trim($url, '/') . '/';
        } else {
            $this->info->{$name . 'Url'} = $url;
        }
        return;
    }

    public function getUrl($name)
    {
        return (isset($this->info->{$name . 'Url'})) ? $this->info->{$name . 'Url'} : null;
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function fetchInformation($crawlerName, Selector ...$selectors)
    {
        $name = $selectors[0]->getName();

        if (count($selectors) === 1) {
            $this->info->{$name} = $this->parseElement($crawlerName, $selectors[0]);
        } else {
            $this->info->{$name} = $this->parseList($crawlerName, array_shift($selectors), ...$selectors);
        }
    }

    private function parseElement($crawlerName, Selector $selector)
    {
        $crawlerName = $crawlerName . 'Crawler';

        if (!$this->{$crawlerName} instanceof Crawler) {
            return null;
        }

        try {
            $el = $this->{$crawlerName}->filter($selector->getTag());

            return ($selector->getAttr()) ? $el->attr($selector->getAttr()) : $el->text();
        } catch (InvalidArgumentException $e) {
            return null;
        }
    }

    private function parseList($crawlerName, Selector $tag, Selector ...$subTags)
    {
        $crawlerName = $crawlerName . 'Crawler';

        if (!$this->{$crawlerName} instanceof Crawler) {
            return null;
        }

        $lists = array();
        $tag = $tag->getTag();

        try {
            $this->{$crawlerName}->filter($tag)->each(function ($node) use (&$lists, $subTags) {

                $item = new Object();

                $crawlerId = uniqid() . 'Crawler';
                $this->makeCrawler((string)$crawlerId, 'alreadyMade', $node);

                foreach ($subTags as $tag) {
                    if (!$tag instanceof Selector) {
                        continue;
                    }

                    $item->{$tag->getName()} = $this->parseElement($crawlerId, $tag);
                }

                array_push($lists, $item);
            });

        } catch (InvalidArgumentException $e) {
            // probably the user don't have lists
        }

        return $lists;
    }

    public function makeCrawler($name, $method, $content)
    {
        if ($method === 'alreadyMade') {
            $this->{$name . 'Crawler'} = $content;
            return;
        }

        $this->{$name . 'Crawler'} = $this->client->request($method, $content);
        return;
    }
}