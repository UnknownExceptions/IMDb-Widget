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
class Selector
{
    private $name, $tag, $attr;

    /**
     * Constructor
     *
     * @param string $name Selector name.
     * @param string $tag Selector tag.
     * @param null|string $attr Selector attribute.
     */
    public function __construct($name, $tag, $attr = null)
    {
        $this->setName($name);
        $this->setTag($tag);
        $this->setAttr($attr);
    }

    /**
     * Tag setter
     *
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * Attribute setter.
     *
     * @param string $attr
     */
    public function setAttr($attr)
    {
        $this->attr = $attr;
    }

    /**
     * Tag getter
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Attribute getter
     *
     * @return null|string
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Name getter
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Name setter
     *
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}