<?php

namespace Sioweb\DummyBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class Extension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        // Es könnten auch alle Services in die selbe Datei
        // Das wäre allerdings unübersichtlicher

        // Listener reagieren auf events / hooks
        $loader->load('listener.yml');

        // Services können aufgerufen und für Dependency Injection
        // verwendet werden
        $loader->load('services.yml');
    }
}
