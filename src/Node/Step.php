<?php

namespace vanare\BehatJunitFormatter\Node;

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
    private $match = [ 'location' => '' ];

    /**
     * @var array
     */
    private $embeddings = [];

    /**
     * @var int
     */
    private $duration = 0;

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
            'duration' => $this->getDuration() * 1000 * 1000000,
        ];
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

}
