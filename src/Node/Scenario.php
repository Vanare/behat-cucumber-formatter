<?php

namespace vanare\BehatJunitFormatter\Node;

use emuse\BehatHTMLFormatter\Classes\Scenario as BaseScenario;

class Scenario extends BaseScenario
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
     * @return Step[]
     */
    public function getSteps()
    {
        return BaseScenario::getSteps();
    }

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
}
