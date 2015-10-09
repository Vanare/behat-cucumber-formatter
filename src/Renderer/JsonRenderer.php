<?php

namespace App\Renderer;

use App\Formatter\FormatterInterface;

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
            $currentSuite = [];

            foreach ($suite->getFeatures() as $feature) {
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
                        $currentStep = [
                            'keyword' => $step->getKeyword(),
                            'name' => $step->getName(),
                            'line' => $step->getLine(),
                            'embeddings' => $step->getEmbeddings(),
                            'match' => $step->getMatch(),
                            'result' => $step->getProcessedResult(),
                        ];

                        array_push($currentScenario['steps'], $currentStep);
                    }

                    foreach ($scenario->getExamples() as $example) {
                        $currentExample = [
                            'keyword' => $example->getKeyword(),
                            'name' => $example->getName(),
                            'line' => $example->getLine(),
                            'description' => $example->getDescription(),
                            'id' => $example->getId(),
                            'rows' => [],
                        ];

                        foreach ($example->getRows() as $row) {
                            $currentRow = [
                                'cells' => $row->getCells(),
                                'id' => $row->getId(),
                                'line' => $row->getLine(),
                            ];

                            array_push($currentExample['rows'], $currentRow);
                        }

                        array_push($currentScenario['examples'], $currentExample);
                    }

                    array_push($currentFeature['elements'], $currentScenario);
                }

                array_push($currentSuite, $currentFeature);
            }

            array_push($this->result, $currentSuite);
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
}
