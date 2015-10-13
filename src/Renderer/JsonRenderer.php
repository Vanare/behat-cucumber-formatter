<?php

namespace vanare\BehatJunitFormatter\Renderer;

use vanare\BehatJunitFormatter\Formatter\FormatterInterface;
use behatJunitFormatter\Node;
use vanare\BehatJunitFormatter\Renderer\RendererInterface;

class JsonRenderer implements RendererInterface
{
    /**
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $result = [];

    /**
     * @param FormatterInterface $formatter
     */
    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     */
    public function render()
    {
        $suites = $this->formatter->getSuites();

        foreach ($suites as $suite) {
            array_push($this->result, $this->processSuite($suite));
        }
    }

    /**
     * @param bool|true $asString
     *
     * @return array|string
     */
    public function getResult($asString = true)
    {
        if ($asString) {
            return json_encode($this->result);
        }

        return $this->result;
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\Suite $suite
     *
     * @return array
     */
    protected function processSuite(\vanare\BehatJunitFormatter\Node\Suite $suite)
    {
        $currentSuite = [];

        foreach ($suite->getFeatures() as $feature) {
            array_push($currentSuite, $this->processFeature($feature));
        }

        return $currentSuite;
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\Feature $feature
     *
     * @return array
     */
    protected function processFeature(\vanare\BehatJunitFormatter\Node\Feature $feature)
    {
        $currentFeature = [
            'uri' => $feature->getUri(),
            'id' => $feature->getId(),
            'keyword' => $feature->getKeyword(),
            'name' => $feature->getName(),
            'line' => $feature->getLine(),
            'description' => $feature->getDescription(),
            'elements' => [],
        ];

        foreach ($feature->getScenarios() as $scenario) {
            array_push($currentFeature['elements'], $this->processScenario($scenario));
        }

        return $currentFeature;
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\Scenario $scenario
     *
     * @return array
     */
    protected function processScenario(\vanare\BehatJunitFormatter\Node\Scenario $scenario)
    {
        $currentScenario = [
            'id' => $scenario->getId(),
            'keyword' => $scenario->getKeyword(),
            'name' => $scenario->getName(),
            'line' => $scenario->getLine(),
            'description' => $scenario->getDescription(),
            'type' => $scenario->getType(),
            'steps' => [],
            'examples' => [],
        ];

        foreach ($scenario->getSteps() as $step) {
            array_push($currentScenario['steps'], $this->processStep($step));
        }

        foreach ($scenario->getExamples() as $example) {
            array_push($currentScenario['examples'], $this->processExample($example));
        }

        return $currentScenario;
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\Step $step
     *
     * @return array
     */
    protected function processStep(\vanare\BehatJunitFormatter\Node\Step $step)
    {
        return [
            'keyword' => $step->getKeyword(),
            'name' => $step->getName(),
            'line' => $step->getLine(),
            'embeddings' => $step->getEmbeddings(),
            'match' => $step->getMatch(),
            'result' => $step->getProcessedResult(),
        ];
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\Example $example
     *
     * @return array
     */
    protected function processExample(\vanare\BehatJunitFormatter\Node\Example $example)
    {
        $currentExample = [
            'keyword' => $example->getKeyword(),
            'name' => $example->getName(),
            'line' => $example->getLine(),
            'description' => $example->getDescription(),
            'id' => $example->getId(),
            'rows' => [],
        ];

        foreach ($example->getRows() as $row) {
            array_push($currentExample['rows'], $this->processExampleRow($row));
        }

        return $currentExample;
    }

    /**
     * @param \vanare\BehatJunitFormatter\Node\ExampleRow $exampleRow
     *
     * @return array
     */
    protected function processExampleRow(\vanare\BehatJunitFormatter\Node\ExampleRow $exampleRow)
    {
        return [
            'cells' => $exampleRow->getCells(),
            'id' => $exampleRow->getId(),
            'line' => $exampleRow->getLine(),
        ];
    }
}
