<?php

namespace vanare\BehatJunitFormatter\Node;

use vanare\BehatJunitFormatter\Node\Scenario;
use emuse\BehatHTMLFormatter\Classes\Feature as BaseFeature;

class Feature extends BaseFeature
{
    /**
     * @var string
     */
    private $keyword = '';

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var int
     */
    private $line = 0;

    /**
     * @return mixed
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param int $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return Scenario[]
     */
    public function getScenarios()
    {
        return BaseFeature::getScenarios();
    }
}
