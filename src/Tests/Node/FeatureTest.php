<?php

namespace Vanare\BehatCucumberJsonFormatter\Tests\Node;

use Vanare\BehatCucumberJsonFormatter\Node;

class FeatureTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function getId()
    {
        $name = 'This is a test name, test name for awesome feature';
        $expectedId = 'this-is-a-test-name,-test-name-for-awesome-feature';

        $feature = $this->createFeature();
        $feature->setName($name);

        $this->assertEquals($expectedId, $feature->getId());
    }

    /**
     * @test
     */
    public function getUri()
    {
        $file = 'features/one_passing_one_failing.feature';

        $feature = $this->createFeature();
        $feature->setFile($file);

        $this->assertEquals($file, $feature->getUri());
    }

    /**
     * @return Node\Feature
     */
    protected function createFeature()
    {
        return new Node\Feature();
    }

}
