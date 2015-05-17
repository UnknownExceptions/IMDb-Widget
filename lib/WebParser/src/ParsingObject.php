<?php

namespace WebParser;

class ParsingObject extends ParsingFunctions
{

    private $parentSelector,
        $subSelectors = array(),
        $lastThingAdded;

    public function __construct($crawler, $parentSelector)
    {
        parent::__construct($crawler);

        $this->parentSelector = new Selector('parent', $parentSelector);
        $this->lastThingAdded = 'parentSelector';
    }

    public function attr($attr)
    {
        if ($this->lastThingAdded === 'subSelectors') {
            $this->{$this->lastThingAdded}[count($this->subSelectors) - 1]->setAttr($attr);
        } else {
            $this->{$this->lastThingAdded}->setAttr($attr);
        }
        return $this;
    }

    public function get()
    {
        if (empty($this->subSelectors)) {
            return $this->parseElement($this->parentSelector);
        } else {
            return $this->parseList($this->parentSelector, ...$this->subSelectors);
        }
    }

    public function selectEach($selector)
    {
        $this->{$this->lastThingAdded}->setTag($this->{$this->lastThingAdded}->getTag() . ' ' . $selector);
        return $this;
    }

    public function getProperty($propertyTag)
    {
        array_push($this->subSelectors, new Selector(null, $propertyTag));
        $this->lastThingAdded = 'subSelectors';
        return $this;
    }

    public function called($propertyName)
    {
        $this->subSelectors[count($this->subSelectors) - 1]->setName($propertyName);
        return $this;
    }
}