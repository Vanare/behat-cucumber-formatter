<?php

namespace App;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class Extension implements ExtensionInterface
{
    public function process(ContainerBuilder $container)
    {
    }

    public function getConfigKey()
    {
        return 'configKey';
    }

    public function initialize(ExtensionManager $extensionManager)
    {
    }

    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode('filename')->defaultValue('report.json');
        $builder->children()->scalarNode('outputDir')->defaultValue('build/tests');
    }

    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('App\\Formatter\\Formatter');

        $definition->addArgument($config['filename']);
        $definition->addArgument($config['outputDir']);

        $container
            ->setDefinition('json.formatter', $definition)
            ->addTag('output.formatter')
        ;
    }
}
