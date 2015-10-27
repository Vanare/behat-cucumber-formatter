<?php


namespace Vanare\BehatCucumberJsonFormatter\Tests\Node;


use Vanare\BehatCucumberJsonFormatter\Node;

class ScenarioTest extends \PHPUnit_Framework_TestCase
{

    const FEATURE_ID = 'test-feature';

    /**
     * @test
     */
    public function getId()
    {
        $name = 'This is a test name, test name for awesome feature';
        $expectedId = sprintf('this-is-a-test-name,-test-name-for-awesome-feature;%s', static::FEATURE_ID);

        $scenario = $this->createScenario();
        $scenario->setName($name);

        $this->assertEquals($expectedId, $scenario->getId());
    }

    /**
     * @return Node\Scenario
     */
    protected function createScenario()
    {

        $feature = $this
            ->getMockBuilder(Node\Feature::class)
            ->getMock();

        $feature
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(static::FEATURE_ID));

        $scenario = new Node\Scenario();
        $scenario->setFeature($feature);

        return $scenario;
    }
}
