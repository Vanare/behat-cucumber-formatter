<?php

namespace fourxxi\BehatCucumberJsonFormatter\Tests\Formatter;

use fourxxi\BehatCucumberJsonFormatter\Formatter\Formatter;
use fourxxi\BehatCucumberJsonFormatter\Node;
use Behat\Behat\Definition\SearchResult;
use Behat\Behat\EventDispatcher\Event\StepTested;
use Behat\Testwork\Call\Call;
use Behat\Behat\Tester\Result;
use Behat\Testwork\Call\CallResult;

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $step;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $scenario;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $afterStepTestedEvent;

    /**
     * @var Formatter
     */
    protected $formatter;

    /**
     *
     */
    public function setUp()
    {
        $this->step = $this->createStep();
        $this->scenario = $this->createScenario();
        $this->afterStepTestedEvent = $this->createAfterStepTestedEvent();
        $this->formatter = $this->createFormatter();
        $this->formatter->onBeforeStepTested($this->createBeforeStepTestedEvent());
    }

    /**
     * @test
     */
    public function onAfterUndefinedStepTested()
    {
        $this->formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createUndefinedStepResult()))
        ;

        $this->formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($this->formatter->getPendingSteps()));
    }

    /**
     * @test
     */
    public function onAfterSkippedStepTested()
    {
        $this->formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createSkippedStepResult()))
        ;

        $this->formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($this->formatter->getSkippedSteps()));
    }

    /**
     * @test
     */
    public function onAfterFailedStepTested()
    {
        $this->formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createFailedStepResult()))
        ;

        $this->formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($this->formatter->getFailedSteps()));
    }

    /**
     * @test
     */
    public function onAfterPassedStepTested()
    {
        $this->formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createPassedStepResult()))
        ;

        $this->formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($this->formatter->getPassedSteps()));
    }

    /**
     * @return Formatter
     */
    protected function createFormatter()
    {
        return new Formatter('filename.json', dirname(__FILE__).DIRECTORY_SEPARATOR.'build');
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createScenario()
    {
        $scenario = $this
            ->getMockBuilder(Node\Scenario::class)
            ->setMethods(['addStep'])
            ->getMock();

        return $scenario;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStep()
    {
        $step = $this
            ->getMockBuilder(Node\Step::class)
            ->setMethods([
                'setDefinition',
                'setKeyword',
                'setText',
                'setLine',
                'setArguments',
                'setResult',
                'setResultCode',
            ])
            ->getMock();

        return $step;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createAfterStepTestedEvent()
    {
        $event = $this
            ->getMockBuilder(StepTested::class)
            ->setMethods(['getFeature', 'getStep', 'getTestResult'])
            ->disableOriginalConstructor()
            ->getMock();
        $event
            ->expects($this->any())
            ->method('getStep')
            ->will($this->returnValue($this->step));

        return $event;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createBeforeStepTestedEvent()
    {
        $event = $this
            ->getMockBuilder(StepTested::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $event;
    }

    /**
     * @return Result\UndefinedStepResult
     */
    protected function createUndefinedStepResult()
    {
        return new Result\UndefinedStepResult();
    }

    /**
     * @return Result\SkippedStepResult
     */
    protected function createSkippedStepResult()
    {
        return new Result\SkippedStepResult(new SearchResult());
    }

    /**
     * @return Result\FailedStepSearchResult
     */
    protected function createFailedStepResult()
    {
        $exception = $this
            ->getMockBuilder(\Exception::class)
            ->setMethods(['getMessage'])
            ->getMock();

        return $this->createExecutedStepResult($exception);
    }

    /**
     * @return Result\ExecutedStepResult
     */
    protected function createPassedStepResult()
    {
        return $this->createExecutedStepResult();
    }

    /**
     * @param null $exception
     *
     * @return Result\ExecutedStepResult
     */
    protected function createExecutedStepResult($exception = null)
    {
        $call = $this->getMockBuilder(Call::class)->getMock();
        $callResult = new CallResult($call, 'return', $exception);

        return new Result\ExecutedStepResult(new SearchResult(), $callResult);
    }
}
