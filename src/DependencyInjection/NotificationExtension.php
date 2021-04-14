<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 16:50
 */

namespace Demroos\NotificationBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class NotificationExtension extends Extension
{

    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        if(isset($config['sender'])) {
            $container->setAlias('notification.sender', $config['sender']);
        }

        $managerDefinition = $container->getDefinition('notification.manager');
        foreach ($config['entities'] as $entityDefinition) {
            $managerDefinition->addMethodCall('addEntity', [
                $entityDefinition['name'],
                $entityDefinition['class']
            ]);
        }
    }
}
