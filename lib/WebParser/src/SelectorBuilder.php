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

use Symfony\Component\DomCrawler\Crawler;

/**
 * Object
 *
 * @author Henrique Dias <hacdias@gmail.com>
 * @author Luís Soares <lsoares@gmail.com>
 */
class SelectorBuilder extends Base
{
    const parent = 'parentSelector',
        child = 'childSelectors';

    private $parentSelector,
        $childSelectors = array(),
        $lastThingModified;

    /**
     * Constructor
     *
     * @param Crawler $crawler
     * @param string $parentSelector
     */
    public function __construct(Crawler $crawler, $parentSelector)
    {
        parent::__construct($crawler);
        $this->parentSelector = new Selector('parent', $parentSelector);
        $this->lastThingModified = self::parent;
    }

    /**
     * Attribute setter
     *
     * @param string $attr
     * @return $this
     */
    public function attr($attr)
    {
        if ($this->lastThingModified === self::parent) {
            $this->parentSelector->setAttr($attr);
            return $this;
        }

        $this->childSelectors[count($this->childSelectors) - 1]->setAttr($attr);
        return $this;
    }

    /**
     * Get data
     *
     * @return array|null|string
     */
    public function get()
    {
        if (empty($this->childSelectors)) {
            return $this->parseElement($this->parentSelector);
        }
		
        return $this->parseList($this->parentSelector, ...$this->childSelectors);
    }
	
    /**
     * Get property
     *
     * @param string $propertyTag
     * @return $this
     */
    public function prop($propertyTag)
    {
        array_push($this->childSelectors, new Selector(null, $propertyTag));
        $this->lastThingModified = self::child;
        return $this;
    }

    /**
     * Called
     *
     * @param string $propertyName
     * @return $this
     */
    public function named($propertyName)
    {
        $this->childSelectors[count($this->childSelectors) - 1]->setName($propertyName);
        return $this;
    }
}