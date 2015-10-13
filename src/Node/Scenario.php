<?php

namespace vanare\BehatJunitFormatter\Node;

use vanare\BehatJunitFormatter\Node\Step;
use emuse\BehatHTMLFormatter\Classes\Scenario as BaseScenario;
use vanare\BehatJunitFormatter\Node\Example;

class Scenario extends BaseScenario
{
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
        return $this->type;
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
}
