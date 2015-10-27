<?php

namespace Vanare\BehatCucumberJsonFormatter\Node;

class Scenario
{

    /**
     * @var Feature
     */
    private $feature;

    /**
     * @var string
     */
    private $keyword = '';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $type = '';

    /**
     * @var Example[]
     */
    private $examples = [];

    /**
     * @var int
     */
    private $id;

    /**
     * @var mixed
     */
    private $name;

    /**
     * @var mixed
     */
    private $line;

    /**
     * @var mixed
     */
    private $tags;

    /**
     * @var mixed
     */
    private $loopCount;

    /**
     * @var bool
     */
    private $passed;

    /**
     * @var Step[]
     */
    private $steps;

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return mb_strtolower($this->type, 'UTF-8');
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Example[]
     */
    public function getExamples()
    {
        return $this->examples;
    }

    /**
     * @param Example[] $examples
     */
    public function setExamples($examples)
    {
        $this->examples = $examples;
    }

    /**
     * @return Feature
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * @param Feature $feature
     */
    public function setFeature($feature)
    {
        $this->feature = $feature;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return sprintf(
            '%s;%s',
            preg_replace('/\s/', '-', mb_strtolower($this->getName(), 'UTF-8')),
            $this->getFeature()->getId()
        );
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLoopCount()
    {
        return $this->loopCount;
    }

    /**
     * @param int $loopCount
     */
    public function setLoopCount($loopCount)
    {
        $this->loopCount = $loopCount;
    }

    /**
     * @return mixed
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param mixed $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return boolean
     */
    public function isPassed()
    {
        return $this->passed;
    }

    /**
     * @param boolean $passed
     */
    public function setPassed($passed)
    {
        $this->passed = $passed;
    }

    /**
     * @return Step[]
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param Step[] $steps
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
    }

    /**
     * @param Step $step
     */
    public function addStep($step)
    {
        $this->steps[] = $step;
    }

    /**
     * @return float|int
     */
    public function getLoopSize()
    {
        return $this->loopCount > 0 ? sizeof($this->steps)/$this->loopCount : sizeof($this->steps);
    }
}
