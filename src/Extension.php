<?php

namespace Vanare\BehatCucumberJsonFormatter;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class Extension implements ExtensionInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * @return string
     */
    public function getConfigKey()
    {
        return 'configKey';
    }

    /**
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode('filename')->defaultValue('report.json');
        $builder->children()->scalarNode('outputDir')->defaultValue('build/tests');
        $builder->children()->booleanNode('enableExtraExceptionData')->defaultFalse();
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition('Vanare\\BehatCucumberJsonFormatter\\Formatter\\Formatter');

        $definition->addArgument($config['filename']);
        $definition->addArgument($config['outputDir']);
        $definition->addArgument($config['enableExtraExceptionData']);

        $container
            ->setDefinition('json.formatter', $definition)
            ->addTag('output.formatter')
        ;
    }
}
