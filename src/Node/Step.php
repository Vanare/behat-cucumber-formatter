<?php

namespace Vanare\BehatCucumberJsonFormatter\Node;

use Behat\Behat\Tester\Result\StepResult;
use Behat\Testwork\Tester\Result\TestResult;

class Step
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
        StepResult::FAILED => 'failed',
        StepResult::PASSED => 'passed',
        StepResult::SKIPPED => 'skipped',
        StepResult::PENDING => 'pending',
        StepResult::UNDEFINED => 'pending',
    ];

    /**
     * @var mixed
     */
    private $keyword;

    /**
     * @var mixed
     */
    private $text;

    /**
     * @var mixed
     */
    private $arguments;

    /**
     * @var mixed
     */
    private $line;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @var mixed
     */
    private $resultCode;

    /**
     * @var mixed
     */
    private $exception;

    /**
     * @var mixed
     */
    private $output;

    /**
     * @var mixed
     */
    private $definition;

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
        $status = StepResult::SKIPPED;

        if (!empty(static::$resultLabels[$this->getResultCode()])) {
            $status = static::$resultLabels[$this->getResultCode()];
        }

        return [
            'status' => $status,
            'error_message' => $this->getException(),
            'duration' => $this->getDuration() * 1000 * 1000000,
        ];
    }

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
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param mixed $arguments
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
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
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param mixed $exception
     */
    public function setException($exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param mixed $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return mixed
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param mixed $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    /**
     * @param mixed $resultCode
     */
    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
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
