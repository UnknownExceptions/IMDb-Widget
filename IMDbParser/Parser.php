<?php

namespace IMDbParser;

require_once 'Selector.php';

use Goutte\Client as WebScrapper;
use InvalidArgumentException;
use stdClass;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    public function __construct($userId)
    {
        $this->info = new stdClass();
        $this->info->userId = $userId;
        $this->info->baseUrl = 'http://www.imdb.com';
        $this->info->profileUrl = $this->info->baseUrl . '/user/' . $userId . '/';
        $this->info->ratingsUrl = $this->info->profileUrl . 'ratings';
        $this->info->listsUrl = $this->info->profileUrl . 'lists';
        $this->info->boardsUrl = $this->info->profileUrl . 'boards';
        $this->info->watchlistUrl = $this->info->profileUrl . 'watchlist';
        $this->info->checkinsUrl = $this->info->profileUrl . 'checkins';
        $this->info->commentsUrl = $this->info->profileUrl . 'comments-index';
        $this->info->pollsUrl = $this->info->profileUrl . '#pollResponses';
        $this->info->ratingsUrlRss = str_replace('www', 'rss', $this->info->ratingsUrl);

        $this->client = new WebScrapper();
        $this->makeCrawler('profile', 'GET', $this->info->profileUrl);
    }

    private function makeCrawler($name, $method, $content)
    {
        if ($method === 'alreadyMade') {
            $this->{$name . 'Crawler'} = $content;
            return;
        }

        $this->{$name . 'Crawler'} = $this->client->request($method, $content);
        return;
    }

    public function getInfo()
    {
        $this->info->nick = $this->parseElement('profile',
            new Selector('nick', '.header h1'));

        $this->info->avatar = $this->parseElement('profile',
            new Selector('avatar', '#avatar-frame img', 'stc'));

        $this->info->memberSince = $this->parseElement('profile',
            new Selector('memberSince', '.header .timestamp'));

        $this->info->bio = $this->parseElement('profile',
            new Selector('bio', '.header .biography'));

        $this->info->badges = $this->parseList('profile',
            new Selector('badges', '.badges .badge-frame'),
            new Selector('name', '.name'),
            new Selector('value', '.value'),
            new Selector('image', '.badge-icon', 'class'));

        $this->info->lists = $this->parseList('profile',
            new Selector('lists', '.lists .user-list'),
            new Selector('name', '.list-name'),
            new Selector('link', '.list-meta', 'href'),
            new Selector('meta', '.list-meta'));

        $this->info->ratings = $this->parseList('profile',
            new Selector('ratings', '.ratings .item'),
            new Selector('href', 'a', 'href'),
            new Selector('title', 'div a'));

        return $this->info;
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

                $item = new stdClass();

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
}