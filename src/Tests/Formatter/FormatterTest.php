<?php

namespace behatJunitFormatter\Tests\Formatter;

use behatJunitFormatter\Formatter\Formatter;
use Behat\Behat\Definition\SearchResult;
use Behat\Behat\EventDispatcher\Event\StepTested;
use Behat\Testwork\Call\Call;
use behatJunitFormatter\Node;
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
     *
     */
    public function setUp()
    {
        $this->step = $this->createStep();
        $this->scenario = $this->createScenario();
        $this->afterStepTestedEvent = $this->createAfterStepTestedEvent();
    }

    /**
     * @test
     */
    public function onAfterUndefinedStepTested()
    {
        $formatter = $this->createFormatter();
        $formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createUndefinedStepResult()))
        ;

        $formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($formatter->getPendingSteps()));
    }

    /**
     * @test
     */
    public function onAfterSkippedStepTested()
    {
        $formatter = $this->createFormatter();
        $formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createSkippedStepResult()))
        ;

        $formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($formatter->getSkippedSteps()));
    }

    /**
     * @test
     */
    public function onAfterFailedStepTested()
    {
        $formatter = $this->createFormatter();
        $formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createFailedStepResult()))
        ;

        $formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($formatter->getFailedSteps()));
    }

    /**
     * @test
     */
    public function onAfterPassedStepTested()
    {
        $formatter = $this->createFormatter();
        $formatter->setCurrentScenario($this->scenario);

        $this->afterStepTestedEvent
            ->expects($this->once())
            ->method('getTestResult')
            ->will($this->returnValue($this->createPassedStepResult()))
        ;

        $formatter->onAfterStepTested($this->afterStepTestedEvent);

        $this->assertEquals(1, count($formatter->getPassedSteps()));
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
