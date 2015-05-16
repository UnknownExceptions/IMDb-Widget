<?php

namespace IMDbParser;

class Element
{

    private $name, $tag, $attr;

    public function __construct( $name, $tag, $attr = null )
    {
        $this->name = $name;
        $this->tag  = $tag;
        $this->attr = $attr;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName( $name )
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag( $tag )
    {
        $this->tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * @param mixed $attr
     */
    public function setAttr( $attr )
    {
        $this->attr = $attr;
    }

}