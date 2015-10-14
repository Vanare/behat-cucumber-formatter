<?php

/**
 * Created by PhpStorm.
 * User: evgenyg
 * Date: 09/10/15
 * Time: 15:29.
 */
namespace vanare\BehatJunitFormatter\Tests\Renderer;

use vanare\BehatJunitFormatter\Node;
use vanare\BehatJunitFormatter\Renderer\JsonRenderer;
use vanare\BehatJunitFormatter\Formatter\FormatterInterface;

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
        $this->step = $this->getMockBuilder(Node\Step::class)->getMock();
        $this->example = $this->getMockBuilder(Node\Example::class)->getMock();
        $this->exampleRow = $this->getMockBuilder(Node\ExampleRow::class)->getMock();
        $this->scenario = $this->getMockBuilder(Node\Scenario::class)->getMock();
        $this->suite = $this->getMockBuilder(Node\Suite::class)->getMock();
        $this->feature = $this->getMockBuilder(Node\Feature::class)->getMock();
        $this->formatter = $this->getMockBuilder(FormatterInterface::class)->getMock();

        $this->generateMockStructure();
    }

    /**
     * @test
     */
    public function renderShouldGenerateValidStructure()
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

        // Feature
        $feature = array_pop($suite);
        $keys = ['uri', 'id', 'keyword', 'name', 'line', 'description', 'elements'];
        $this->assertArrayHasKeys($keys, $feature);
        $this->assertTrue(is_array($feature['elements']));
        $this->assertEquals(2, count($feature['elements']));

        // Scenario
        $scenario = array_pop($feature['elements']);
        $keys = ['id', 'keyword', 'name', 'line', 'description', 'type', 'steps'];
        $this->assertArrayHasKeys($keys, $scenario);
        $this->assertTrue(is_array($scenario['steps']));
        $this->assertTrue(is_array($scenario['examples']));
        $this->assertEquals(3, count($scenario['steps']));
        $this->assertEquals(2, count($scenario['examples']));

        // Step
        $step = array_pop($scenario['steps']);
        $keys = ['keyword', 'name', 'line', 'embeddings', 'match', 'result'];
        $this->assertArrayHasKeys($keys, $step);

        // Example
        $example = array_pop($scenario['examples']);
        $keys = ['keyword', 'name', 'line', 'description', 'id', 'rows'];
        $this->assertArrayHasKeys($keys, $example);
        $this->assertTrue(is_array($example['rows']));
        $this->assertEquals(2, count($example['rows']));

        // ExampleRow
        $row = array_pop($example['rows']);
        $keys = ['cells', 'line', 'id'];
        $this->assertArrayHasKeys($keys, $row);
    }

    /**
     * @test
     */
    public function getResultShouldReturnValidJsonString()
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

    /**
     * @param array  $keys
     * @param array  $array
     * @param string $message
     */
    protected function assertArrayHasKeys(array $keys, array $array, $message = '')
    {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array, $message);
        }
    }
}
