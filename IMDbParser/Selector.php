<?php

namespace IMDbParser;

class Selector
{
    private $name, $tag, $attr;

    public function __construct($name, $tag, $attr = null)
    {
        $this->setName($name);
        $this->setTag($tag);
        $this->setAttr($attr);
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function setAttr($attr)
    {
        $this->attr = $attr;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getAttr()
    {
        return $this->attr;
    }
}