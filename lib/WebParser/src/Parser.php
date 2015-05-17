<?php

namespace WebParser;

use Goutte\Client as Client;

class Parser
{
    private $url, $client, $crawler;

    public function __construct($url)
    {
        $this->url = $url;
        $this->client = new Client();
        $this->crawler = $this->client->request('GET', $this->url);
    }

    public function find($selector)
    {
        $parsingObject = new ParsingObject($this->crawler, $selector);
        return $parsingObject;
    }
}