<?php

/**
 * Created by PhpStorm.
 * User: evgenyg
 * Date: 09/10/15
 * Time: 15:29.
 */
namespace App\Tests\Renderer;

use App\Node\Step;
use App\Node\Scenario;
use App\Node\Feature;
use App\Node\Suite;
use App\Node\Example;
use App\Node\ExampleRow;
use App\Renderer\JsonRenderer;
use App\Formatter\FormatterInterface;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $exampleRow;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $example;

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
    protected $suite;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $feature;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formatter;

    /**
     *
     */
    public function setUp()
    {
        $this->step = $this->getMockBuilder(Step::class)->getMock();
        $this->example = $this->getMockBuilder(Example::class)->getMock();
        $this->exampleRow = $this->getMockBuilder(ExampleRow::class)->getMock();
        $this->scenario = $this->getMockBuilder(Scenario::class)->getMock();
        $this->suite = $this->getMockBuilder(Suite::class)->getMock();
        $this->feature = $this->getMockBuilder(Feature::class)->getMock();
        $this->formatter = $this->getMockBuilder(FormatterInterface::class)->getMock();

        $this->generateMockStructure();
    }

    /**
     *
     */
    public function testRenderShouldGenerateValidStructure()
    {
        $renderer = $this->createRenderer();
        $renderer->render();
        $result = $renderer->getResult(false);

        $this->assertTrue(is_array($result));
        $this->assertEquals(1, count($result));

        /*
         * Run through structure
         */

        // Suite
        $suite = array_pop($result);
        $this->assertTrue(is_array($suite));
        $this->assertEquals(2, count($suite));

        $feature = array_pop($suite);
        // Feature
        $this->assertArrayHasKey('uri', $feature);
        $this->assertArrayHasKey('id', $feature);
        $this->assertArrayHasKey('keyword', $feature);
        $this->assertArrayHasKey('name', $feature);
        $this->assertArrayHasKey('line', $feature);
        $this->assertArrayHasKey('description', $feature);
        $this->assertArrayHasKey('elements', $feature);
        $this->assertTrue(is_array($feature['elements']));
        $this->assertEquals(2, count($feature['elements']));

        // Scenario
        $scenario = array_pop($feature['elements']);
        $this->assertArrayHasKey('id', $scenario);
        $this->assertArrayHasKey('keyword', $scenario);
        $this->assertArrayHasKey('name', $scenario);
        $this->assertArrayHasKey('line', $scenario);
        $this->assertArrayHasKey('description', $scenario);
        $this->assertArrayHasKey('type', $scenario);
        $this->assertArrayHasKey('steps', $scenario);
        $this->assertTrue(is_array($scenario['steps']));
        $this->assertTrue(is_array($scenario['examples']));
        $this->assertEquals(3, count($scenario['steps']));
        $this->assertEquals(2, count($scenario['examples']));

        // Step
        $step = array_pop($scenario['steps']);
        $this->assertArrayHasKey('keyword', $step);
        $this->assertArrayHasKey('name', $step);
        $this->assertArrayHasKey('line', $step);
        $this->assertArrayHasKey('embeddings', $step);
        $this->assertArrayHasKey('match', $step);
        $this->assertArrayHasKey('result', $step);

        // Example
        $example = array_pop($scenario['examples']);
        $this->assertArrayHasKey('keyword', $example);
        $this->assertArrayHasKey('name', $example);
        $this->assertArrayHasKey('line', $example);
        $this->assertArrayHasKey('description', $example);
        $this->assertArrayHasKey('id', $example);
        $this->assertArrayHasKey('rows', $example);
        $this->assertTrue(is_array($example['rows']));
        $this->assertEquals(2, count($example['rows']));

        // ExampleRow
        $row = array_pop($example['rows']);
        $this->assertArrayHasKey('cells', $row);
        $this->assertArrayHasKey('line', $row);
        $this->assertArrayHasKey('id', $row);
    }

    /**
     *
     */
    public function testGetResultShouldReturnValidJsonString()
    {
        $renderer = $this->createRenderer();
        $renderer->render();

        $this->assertJson($renderer->getResult());
    }

    /**
     * @return JsonRenderer
     */
    protected function createRenderer()
    {
        return new JsonRenderer($this->formatter);
    }

    /**
     *
     */
    protected function generateMockStructure()
    {
        $this->example
            ->expects($this->any())
            ->method('getRows')
            ->will($this->returnValue([
                $this->exampleRow,
                $this->exampleRow,
            ]));

        $this->scenario
            ->expects($this->any())
            ->method('getSteps')
            ->will($this->returnValue([
                $this->step,
                $this->step,
                $this->step,
            ]));

        $this->scenario
            ->expects($this->any())
            ->method('getExamples')
            ->will($this->returnValue([
                $this->example,
                $this->example,
            ]));

        $this->feature
            ->expects($this->any())
            ->method('getScenarios')
            ->will($this->returnValue([
                $this->scenario,
                $this->scenario,
            ]));

        $this->suite
            ->expects($this->any())
            ->method('getFeatures')
            ->will($this->returnValue([
                $this->feature,
                $this->feature,
            ]));

        $this->formatter
            ->expects($this->any())
            ->method('getSuites')
            ->will($this->returnValue([
                $this->suite,
            ]));
    }
}
