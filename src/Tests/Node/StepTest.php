<?php

namespace vanare\BehatJunitFormatter\Tests\Node;

use vanare\BehatJunitFormatter\Node\Step;
use Behat\Testwork\Tester\Result\TestResult;

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
        $this->assertArrayHasKey('duration', $result);
    }

    /**
     * @param TestResult $result
     * @param $resultCode
     *
     * @return Step
     */
    protected function createStep(TestResult $result, $resultCode)
    {
        $step = new Step();
        $step->setResult($result);
        $step->setResultCode($resultCode);

        return $step;
    }
}
