<?php

namespace Vanare\BehatCucumberJsonFormatter\Tests\Node;

use Vanare\BehatCucumberJsonFormatter\Exception\EnrichedExceptionInterface;
use Vanare\BehatCucumberJsonFormatter\Node\Step;
use Behat\Testwork\Tester\Result\TestResult;

class TestException extends \Exception implements EnrichedExceptionInterface
{
    /** @var bool */
    private $returnExtraMessage;

    public function __construct($message, $returnExtraMessage = true)
    {
        parent::__construct($message);
        $this->returnExtraMessage = $returnExtraMessage;
    }

    public function getExtraMessage()
    {
        if (!$this->returnExtraMessage) {
            return '';
        }

        return <<<JSON
{
  "extra": {
    "key": "value"
  }
}
JSON;

    }
}

class StepTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getProcessedResultReturnsPassedStructure()
    {
        // Arrange
        $passedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::PASSED;
        $step = $this->createStep($passedResult, $resultCode);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(Step::$resultLabels[$resultCode], $result['status']);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertNull($result['error_message']);
        $this->assertArrayHasKey('duration', $result);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructure()
    {
        // Arrange
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertTrue(is_array($result));
        $this->assertArrayHasKey('status', $result);
        $this->assertEquals(Step::$resultLabels[$resultCode], $result['status']);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertNull($result['error_message']);
        $this->assertArrayHasKey('duration', $result);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureIgnoreInvalidExceptionObject()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new \stdClass(), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertNull($result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureIgnoreExtraDataBecauseNoEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new \Exception('Test message 1'), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertEquals('Test message 1', $result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureWithExtraDataBecauseEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new TestException('Test message 2'), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertEquals("Test message 2: \n\n{\n  \"extra\": {\n    \"key\": \"value\"\n  }\n}", $result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureWithoutMessageButWithExtraDataBecauseEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new TestException(''), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertEquals("{\n  \"extra\": {\n    \"key\": \"value\"\n  }\n}", $result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureWithMessageButWithEmptyExtraDataBecauseEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new TestException('Test message 3', false), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertEquals('Test message 3', $result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureWithoutMessageButWithEmptyExtraDataBecauseEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new TestException('', false), true);
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertNull($result['error_message']);
    }

    /**
     * @test
     */
    public function getProcessedResultReturnsFailedStructureIgnoreExtraExtraDataBecauseDisabledByConfigWhileEnrichedExceptionIsGiven()
    {
        $failedResult = $this->getMockBuilder(TestResult::class)->getMock();

        // Act
        $resultCode = TestResult::FAILED;
        $step = $this->createStep($failedResult, $resultCode, new TestException('Test message 4'));
        $result = $step->getProcessedResult();

        // Assert
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('error_message', $result);
        $this->assertArrayHasKey('duration', $result);
        $this->assertEquals('Test message 4', $result['error_message']);
    }

    /**
     * @param TestResult $result
     * @param $resultCode
     * @param null|mixed $exception
     * @param bool $enableExtraExceptionData
     *
     * @return Step
     */
    protected function createStep(TestResult $result, $resultCode, $exception = null, $enableExtraExceptionData = false)
    {
        $step = new Step($enableExtraExceptionData);
        $step->setResult($result);
        $step->setResultCode($resultCode);
        $step->setException($exception);

        return $step;
    }
}
