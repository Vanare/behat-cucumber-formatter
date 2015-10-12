<?php

namespace behatJunitFormatter\Node;

use Behat\Testwork\Tester\Result\TestResult;
use emuse\BehatHTMLFormatter\Classes\Step as BaseStep;

class Step extends BaseStep
{
    /**
     * @var
     */
    private $name = '';

    /**
     * @var array
     */
    private $match = [];

    /**
     * @var array
     */
    private $embeddings = [];

    /**
     * @var array
     */
    public static $resultLabels = [
        TestResult::FAILED => 'failed',
        TestResult::PASSED => 'passed',
        TestResult::SKIPPED => 'skipped',
        TestResult::PENDING => 'pending',
    ];

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
     * @return array
     */
    public function getMatch()
    {
        return $this->match;
    }

    /**
     * @param array $match
     */
    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @return array
     */
    public function getEmbeddings()
    {
        return $this->embeddings;
    }

    /**
     * @param array $embeddings
     */
    public function setEmbeddings($embeddings)
    {
        $this->embeddings = $embeddings;
    }

    /**
     * Process result.
     *
     * @return array
     */
    public function getProcessedResult()
    {
        return [
            'status' => static::$resultLabels[$this->getResultCode()],
            'error_message' => $this->getException(),
            'duration' => 1,
        ];
    }
}
