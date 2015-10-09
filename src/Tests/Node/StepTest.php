<?php

/**
 * Created by PhpStorm.
 * User: evgenyg
 * Date: 09/10/15
 * Time: 17:55.
 */
namespace App\Tests\Node;

use App\Node\Step;
use Behat\Testwork\Tester\Result\TestResult;

class StepTest extends \PHPUnit_Framework_TestCase
{
    public function testGetPassedResult()
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

    public function testGetFailedResult()
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
