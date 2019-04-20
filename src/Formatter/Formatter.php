<?php

namespace Vanare\BehatCucumberJsonFormatter\Formatter;

use Vanare\BehatCucumberJsonFormatter\Renderer\JsonRenderer;
use Vanare\BehatCucumberJsonFormatter\Node;
use Vanare\BehatCucumberJsonFormatter\Renderer\RendererInterface;
use Vanare\BehatCucumberJsonFormatter\Printer\FileOutputPrinter;
use Behat\Behat\EventDispatcher\Event as BehatEvent;
use Behat\Behat\Tester\Result;
use Behat\Testwork\EventDispatcher\Event as TestworkEvent;
use Behat\Testwork\Counter\Memory;
use Behat\Testwork\Counter\Timer;
use Behat\Testwork\Output\Printer\OutputPrinter;
use Behat\Testwork\Tester\Result\TestResult;

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
     * @var OutputPrinter
     */
    private $printer;

    /**
     * @var RendererInterface
     */
    private $renderer;

    /**
     * @var Node\Suite[]
     */
    private $suites;

    /**
     * @var Node\Suite
     */
    private $currentSuite;

    /**
     * @var int
     */
    private $featureCounter = 1;
    /**
     * @var Node\Feature
     */
    private $currentFeature;

    /**
     * @var Node\Scenario
     */
    private $currentScenario;

    /**
     * @var Node\Scenario[]
     */
    private $failedScenarios;

    /**
     * @var Node\Scenario[]
     */
    private $passedScenarios;

    /**
     * @var Node\Feature[]
     */
    private $failedFeatures;

    /**
     * @var Node\Feature[]
     */
    private $passedFeatures;

    /**
     * @var Node\Step[]
     */
    private $failedSteps;

    /**
     * @var Node\Step[]
     */
    private $passedSteps;

    /**
     * @var Node\Step[]
     */
    private $pendingSteps;

    /**
     * @var Node\Step[]
     */
    private $skippedSteps;

    /** @var bool */
    private $enableExtraExceptionData;

    /**
     * @param $filename
     * @param $outputDir
     * @param bool $enableExtraExceptionData
     */
    public function __construct($filename, $outputDir, $enableExtraExceptionData = false)
    {
        $this->renderer = new JsonRenderer($this);
        $this->printer = new FileOutputPrinter($filename, $outputDir);
        $this->timer = new Timer();
        $this->memory = new Memory();
        $this->enableExtraExceptionData = $enableExtraExceptionData;
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
     * @return Node\Suite[]
     */
    public function getSuites()
    {
        return $this->suites;
    }

    /**
     * @return Node\Suite
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
     * @return Node\Feature
     */
    public function getCurrentFeature()
    {
        return $this->currentFeature;
    }

    /**
     * @return Node\Scenario
     */
    public function getCurrentScenario()
    {
        return $this->currentScenario;
    }

    /**
     * @param Node\Scenario $scenario
     */
    public function setCurrentScenario(Node\Scenario $scenario)
    {
        $this->currentScenario = $scenario;
    }

    /**
     * @return Node\Scenario[]
     */
    public function getFailedScenarios()
    {
        return $this->failedScenarios;
    }

    /**
     * @return Node\Scenario[]
     */
    public function getPassedScenarios()
    {
        return $this->passedScenarios;
    }

    /**
     * @return Node\Feature[]
     */
    public function getFailedFeatures()
    {
        return $this->failedFeatures;
    }

    /**
     * @return Node\Feature[]
     */
    public function getPassedFeatures()
    {
        return $this->passedFeatures;
    }

    /**
     * @return Node\Step[]
     */
    public function getFailedSteps()
    {
        return $this->failedSteps;
    }

    /**
     * @return Node\Step[]
     */
    public function getPassedSteps()
    {
        return $this->passedSteps;
    }

    /**
     * @return Node\Step[]
     */
    public function getPendingSteps()
    {
        return $this->pendingSteps;
    }

    /**
     * @return Node\Step[]
     */
    public function getSkippedSteps()
    {
        return $this->skippedSteps;
    }

    /**
     * Triggers before running tests.
     *
     * @param TestworkEvent\BeforeExerciseCompleted $event
     */
    public function onBeforeExercise(TestworkEvent\BeforeExerciseCompleted $event)
    {
        $this->timer->start();
    }

    /**
     * Triggers after running tests.
     *
     * @param TestworkEvent\AfterExerciseCompleted $event
     */
    public function onAfterExercise(TestworkEvent\AfterExerciseCompleted $event)
    {
        $this->timer->stop();

        $this->renderer->render();
        $this->printer->write($this->renderer->getResult());
    }

    /**
     * @param TestworkEvent\BeforeSuiteTested $event
     */
    public function onBeforeSuiteTested(TestworkEvent\BeforeSuiteTested $event)
    {
        $this->currentSuite = new Node\Suite();
        $this->currentSuite->setName($event->getSuite()->getName());
    }

    /**
     * @param TestworkEvent\AfterSuiteTested $event
     */
    public function onAfterSuiteTested(TestworkEvent\AfterSuiteTested $event)
    {
        $this->suites[] = $this->currentSuite;
    }

    /**
     * @param BehatEvent\BeforeFeatureTested $event
     */
    public function onBeforeFeatureTested(BehatEvent\BeforeFeatureTested $event)
    {
        $feature = new Node\Feature();
        $feature->setId($this->featureCounter);
        ++$this->featureCounter;
        $feature->setName($event->getFeature()->getTitle());
        $feature->setDescription($event->getFeature()->getDescription());
        $feature->setTags($event->getFeature()->getTags());
        $feature->setFile($event->getFeature()->getFile());
        $feature->setKeyword($event->getFeature()->getKeyword());
        $this->currentFeature = $feature;
    }

    /**
     * @param BehatEvent\AfterFeatureTested $event
     */
    public function onAfterFeatureTested(BehatEvent\AfterFeatureTested $event)
    {
        $this->currentSuite->addFeature($this->currentFeature);
        if ($this->currentFeature->allPassed()) {
            $this->passedFeatures[] = $this->currentFeature;
        } else {
            $this->failedFeatures[] = $this->currentFeature;
        }
    }

    /**
     * @param BehatEvent\BeforeScenarioTested $event
     */
    public function onBeforeScenarioTested(BehatEvent\BeforeScenarioTested $event)
    {
        $scenario = new Node\Scenario();
        $scenario->setName($event->getScenario()->getTitle());
        $scenario->setTags($event->getScenario()->getTags());
        $scenario->setLine($event->getScenario()->getLine());
        $scenario->setType($event->getScenario()->getNodeType());
        $scenario->setKeyword($event->getScenario()->getKeyword());
        $scenario->setFeature($this->currentFeature);
        $this->currentScenario = $scenario;
    }

    /**
     * @param BehatEvent\AfterScenarioTested $event
     */
    public function onAfterScenarioTested(BehatEvent\AfterScenarioTested $event)
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
     * @param BehatEvent\BeforeOutlineTested $event
     */
    public function onBeforeOutlineTested(BehatEvent\BeforeOutlineTested $event)
    {
        $scenario = new Node\Scenario();
        $scenario->setName($event->getOutline()->getTitle());
        $scenario->setTags($event->getOutline()->getTags());
        $scenario->setLine($event->getOutline()->getLine());
        $scenario->setType($event->getOutline()->getNodeType());
        $scenario->setKeyword($event->getOutline()->getKeyword());
        $scenario->setFeature($this->currentFeature);
        $this->currentScenario = $scenario;
    }

    /**
     * @param BehatEvent\AfterOutlineTested $event
     */
    public function onAfterOutlineTested(BehatEvent\AfterOutlineTested $event)
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
     * @param BehatEvent\BeforeStepTested $event
     */
    public function onBeforeStepTested(BehatEvent\StepTested $event)
    {
        $this->timer->start();
    }

    /**
     * @param BehatEvent\AfterStepTested $event
     */
    public function onAfterStepTested(BehatEvent\StepTested $event)
    {
        $this->timer->stop();

        $result = $event->getTestResult();

        $step = new Node\Step($this->enableExtraExceptionData);
        $step->setKeyword($event->getStep()->getKeyword());
        $step->setName($event->getStep()->getText());
        $step->setLine($event->getStep()->getLine());
        $step->setArguments($event->getStep()->getArguments());
        $step->setResult($result);
        $step->setResultCode($result->getResultCode());
        $step->setDuration($this->timer->getSeconds());

        $this->processStep($step, $result);

        $this->currentScenario->addStep($step);
    }

    public function getName()
    {
        return 'cucumber_json';
    }

    /**
     * @param Node\Step  $step
     * @param TestResult $result
     */
    protected function processStep(Node\Step $step, TestResult $result)
    {
        // Pended
        if (is_a($result, Result\UndefinedStepResult::class)) {
            $this->pendingSteps[] = $step;

            return;
        }

        // Skipped
        if (is_a($result, Result\SkippedStepResult::class)) {
            $step->setDefinition($result->getStepDefinition());
            $this->skippedSteps[] = $step;

            return;
        }

        // Failed or passed
        if ($result instanceof Result\ExecutedStepResult) {
            $step->setDefinition($result->getStepDefinition());
            $exception = $result->getException();
            if ($exception) {
                $step->setException($exception);
                $this->failedSteps[] = $step;
            } else {
                $this->passedSteps[] = $step;
            }

            return;
        }
    }
}
