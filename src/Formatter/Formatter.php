<?php

namespace App\Formatter;

use App\Renderer\JsonRenderer;
use Behat\Behat\EventDispatcher\Event\AfterFeatureTested;
use Behat\Behat\EventDispatcher\Event\AfterOutlineTested;
use Behat\Behat\EventDispatcher\Event\AfterScenarioTested;
use Behat\Behat\EventDispatcher\Event\AfterStepTested;
use Behat\Behat\EventDispatcher\Event\BeforeFeatureTested;
use Behat\Behat\EventDispatcher\Event\BeforeOutlineTested;
use Behat\Behat\EventDispatcher\Event\BeforeScenarioTested;
use Behat\Behat\EventDispatcher\Event\BeforeStepTested;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use Behat\Behat\Tester\Result\UndefinedStepResult;
use Behat\Behat\Tester\Result\SkippedStepResult;
use Behat\Testwork\Counter\Memory;
use Behat\Testwork\Counter\Timer;
use Behat\Testwork\EventDispatcher\Event\AfterExerciseCompleted;
use Behat\Testwork\EventDispatcher\Event\BeforeExerciseCompleted;
use Behat\Testwork\Output\Exception\BadOutputPathException;
use Behat\Testwork\Output\Printer\OutputPrinter;
use Behat\Testwork\Tester\Result\TestResult;
use emuse\BehatHTMLFormatter\Classes\Feature;
use emuse\BehatHTMLFormatter\Classes\Scenario;
use emuse\BehatHTMLFormatter\Classes\Step;
use emuse\BehatHTMLFormatter\Classes\Suite;
use App\Renderer\RendererInterface;
use jarnaiz\JUnitFormatter\Printer\FileOutputPrinter;

class Formatter implements FormatterInterface
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Timer
     */
    private $timer;

    /**
     * @var string
     */
    private $memory;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var string
     */
    private $base_path;

    /**
     * @var OutputPrinter
     */
    private $printer;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Suite[]
     */
    private $suites;

    /**
     * @var Suite
     */
    private $currentSuite;

    /**
     * @var int
     */
    private $featureCounter = 1;
    /**
     * @var Feature
     */
    private $currentFeature;

    /**
     * @var Scenario
     */
    private $currentScenario;

    /**
     * @var Scenario[]
     */
    private $failedScenarios;

    /**
     * @var Scenario[]
     */
    private $passedScenarios;

    /**
     * @var Feature[]
     */
    private $failedFeatures;

    /**
     * @var Feature[]
     */
    private $passedFeatures;

    /**
     * @var Step[]
     */
    private $failedSteps;

    /**
     * @var Step[]
     */
    private $passedSteps;

    /**
     * @var Step[]
     */
    private $pendingSteps;

    /**
     * @var Step[]
     */
    private $skippedSteps;

    public function __construct($filename, $outputDir)
    {
        $this->renderer = new JsonRenderer($this);
        $this->printer = new FileOutputPrinter($filename, $outputDir);
        $this->timer = new Timer();
        $this->memory = new Memory();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'tester.exercise_completed.before' => 'onBeforeExercise',
            'tester.exercise_completed.after' => 'onAfterExercise',
            'tester.suite_tested.before' => 'onBeforeSuiteTested',
            'tester.suite_tested.after' => 'onAfterSuiteTested',
            'tester.feature_tested.before' => 'onBeforeFeatureTested',
            'tester.feature_tested.after' => 'onAfterFeatureTested',
            'tester.scenario_tested.before' => 'onBeforeScenarioTested',
            'tester.scenario_tested.after' => 'onAfterScenarioTested',
            'tester.outline_tested.before' => 'onBeforeOutlineTested',
            'tester.outline_tested.after' => 'onAfterOutlineTested',
            'tester.step_tested.after' => 'onAfterStepTested',
        );
    }

    /**
     * Returns formatter description.
     *
     * @return string
     */
    public function getDescription()
    {
        return 'Cucumber style formatter';
    }

    /**
     * Returns formatter output printer.
     *
     * @return OutputPrinter
     */
    public function getOutputPrinter()
    {
        return $this->printer;
    }

    /**
     * Sets formatter parameter.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Returns parameter name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    /**
     * Verify that the specified output path exists or can be created,
     * then sets the output path.
     *
     * @param String $path Output path relative to %paths.base%
     *
     * @throws BadOutputPathException
     */
    public function setOutputPath($path)
    {
        $outpath = realpath($this->base_path.DIRECTORY_SEPARATOR.$path);
        if (!file_exists($outpath)) {
            if (!mkdir($outpath, 0755, true)) {
                throw new BadOutputPathException(
                    sprintf(
                        'Output path %s does not exist and could not be created!',
                        $outpath
                    ),
                    $outpath
                );
            }
        } else {
            if (!is_dir($outpath)) {
                throw new BadOutputPathException(
                    sprintf(
                        'The argument to `output` is expected to the a directory, but got %s!',
                        $outpath
                    ),
                    $outpath
                );
            }
        }
        $this->outputPath = $outpath;
    }

    /**
     * @return string
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * @return Timer
     */
    public function getTimer()
    {
        return $this->timer;
    }

    /**
     * @return Memory|string
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @return Suite[]
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * @return Suite
     */
    public function getCurrentSuite()
    {
        return $this->currentSuite;
    }

    /**
     * @return int
     */
    public function getFeatureCounter()
    {
        return $this->featureCounter;
    }

    /**
     * @return Feature
     */
    public function getCurrentFeature()
    {
        return $this->currentFeature;
    }

    /**
     * @return Scenario
     */
    public function getCurrentScenario()
    {
        return $this->currentScenario;
    }

    /**
     * @return Scenario[]
     */
    public function getFailedScenarios()
    {
        return $this->failedScenarios;
    }

    /**
     * @return Scenario[]
     */
    public function getPassedScenarios()
    {
        return $this->passedScenarios;
    }

    /**
     * @return Feature[]
     */
    public function getFailedFeatures()
    {
        return $this->failedFeatures;
    }

    /**
     * @return Feature[]
     */
    public function getPassedFeatures()
    {
        return $this->passedFeatures;
    }

    /**
     * @return Step[]
     */
    public function getFailedSteps()
    {
        return $this->failedSteps;
    }

    /**
     * @return Step[]
     */
    public function getPassedSteps()
    {
        return $this->passedSteps;
    }

    /**
     * @return Step[]
     */
    public function getPendingSteps()
    {
        return $this->pendingSteps;
    }

    /**
     * @return Step[]
     */
    public function getSkippedSteps()
    {
        return $this->skippedSteps;
    }

    /**
     * Triggers before running tests.
     *
     * @param BeforeExerciseCompleted $event
     */
    public function onBeforeExercise(BeforeExerciseCompleted $event)
    {
        $this->timer->start();
    }

    /**
     * Triggers after running tests.
     *
     * @param AfterExerciseCompleted $event
     */
    public function onAfterExercise(AfterExerciseCompleted $event)
    {
        $this->timer->stop();

        // Render and write to file
        $this->renderer->render();
        $this->printer->write($this->renderer->getResult());
    }

    /**
     * @param BeforeFeatureTested $event
     */
    public function onBeforeFeatureTested(BeforeFeatureTested $event)
    {
        $feature = new Feature();
        $feature->setId($this->featureCounter);
        ++$this->featureCounter;
        $feature->setName($event->getFeature()->getTitle());
        $feature->setDescription($event->getFeature()->getDescription());
        $feature->setTags($event->getFeature()->getTags());
        $feature->setFile($event->getFeature()->getFile());
        $this->currentFeature = $feature;
    }

    /**
     * @param AfterFeatureTested $event
     */
    public function onAfterFeatureTested(AfterFeatureTested $event)
    {
        $this->currentSuite->addFeature($this->currentFeature);
        if ($this->currentFeature->allPassed()) {
            $this->passedFeatures[] = $this->currentFeature;
        } else {
            $this->failedFeatures[] = $this->currentFeature;
        }
    }

    /**
     * @param BeforeScenarioTested $event
     */
    public function onBeforeScenarioTested(BeforeScenarioTested $event)
    {
        $scenario = new Scenario();
        $scenario->setName($event->getScenario()->getTitle());
        $scenario->setTags($event->getScenario()->getTags());
        $scenario->setLine($event->getScenario()->getLine());
        $this->currentScenario = $scenario;
    }

    /**
     * @param AfterScenarioTested $event
     */
    public function onAfterScenarioTested(AfterScenarioTested $event)
    {
        $scenarioPassed = $event->getTestResult()->isPassed();

        if ($scenarioPassed) {
            $this->passedScenarios[] = $this->currentScenario;
            $this->currentFeature->addPassedScenario();
        } else {
            $this->failedScenarios[] = $this->currentScenario;
            $this->currentFeature->addFailedScenario();
        }

        $this->currentScenario->setPassed($event->getTestResult()->isPassed());
        $this->currentFeature->addScenario($this->currentScenario);
    }

    /**
     * @param BeforeOutlineTested $event
     */
    public function onBeforeOutlineTested(BeforeOutlineTested $event)
    {
        $scenario = new Scenario();
        $scenario->setName($event->getOutline()->getTitle());
        $scenario->setTags($event->getOutline()->getTags());
        $scenario->setLine($event->getOutline()->getLine());
        $this->currentScenario = $scenario;
    }

    /**
     * @param AfterOutlineTested $event
     */
    public function onAfterOutlineTested(AfterOutlineTested $event)
    {
        $scenarioPassed = $event->getTestResult()->isPassed();

        if ($scenarioPassed) {
            $this->passedScenarios[] = $this->currentScenario;
            $this->currentFeature->addPassedScenario();
        } else {
            $this->failedScenarios[] = $this->currentScenario;
            $this->currentFeature->addFailedScenario();
        }

        $this->currentScenario->setPassed($event->getTestResult()->isPassed());
        $this->currentFeature->addScenario($this->currentScenario);
    }

    /**
     * @param BeforeStepTested $event
     */
    public function onBeforeStepTested(BeforeStepTested $event)
    {
    }

    /**
     * @param AfterStepTested $event
     */
    public function onAfterStepTested(AfterStepTested $event)
    {
        $result = $event->getTestResult();

        /** @var Step $step */
        $step = new Step();
        $step->setKeyword($event->getStep()->getKeyword());
        $step->setText($event->getStep()->getText());
        $step->setLine($event->getStep()->getLine());
        $step->setArguments($event->getStep()->getArguments());
        $step->setResult($result);
        $step->setResultCode($result->getResultCode());

        $this->processStep($step, $result);

        $this->currentScenario->addStep($step);
    }

    public function getName()
    {
        return '';
    }

    /**
     * @param Step       $step
     * @param TestResult $result
     */
    protected function processStep(Step &$step, TestResult $result)
    {
        // Pended
        if (is_a($result, UndefinedStepResult::class)) {
            $this->pendingSteps[] = $step;

            return;
        }

        // Skipped
        if (is_a($result, SkippedStepResult::class)) {
            /* @var SkippedStepResult $result */

            $step->setDefinition($result->getStepDefinition());
            $this->skippedSteps[] = $step;

            return;
        }

        // Failed or passed
        if (is_a($result, ExecutedStepResult::class)) {
            /* @var ExecutedStepResult $result */

            $step->setDefinition($result->getStepDefinition());
            $exception = $result->getException();
            if ($exception) {
                $step->setException($exception->getMessage());
                $this->failedSteps[] = $step;
            } else {
                $this->passedSteps[] = $step;
            }

            return;
        }
    }
}
