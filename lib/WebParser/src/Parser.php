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

/**
 * Parser
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class Parser
{
    private $url, $client, $crawler;

    /**
     * Constructor
     *
     * Defines the URL to use with the parser, creates the new Goutte
     * client and the crawler.
     *
     * @param string $url The URL to use with the parser.
     */
    public function __construct($url)
    {
        $this->url = $url;
        $this->client = new Client();
        $this->crawler = $this->client->request('GET', $this->url);
    }
	
    /**
     * Find
     *
     * Creates the new parsing object with the parent selector.
     *
     * @param string $selector The parent html selector.
     * @return SelectorBuilder The parsing object.
     */
    public function find($selector)
    {
        return new SelectorBuilder($this->crawler, $selector);
    }
}