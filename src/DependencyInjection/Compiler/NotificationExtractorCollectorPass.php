<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 25.07.2019
 * Time: 17:07
 */

namespace Demroos\NotificationBundle\DependencyInjection\Compiler;

use Demroos\NotificationBundle\Manager\NotificationManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NotificationExtractorCollectorPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('notification.manager')) {
            return;
        }

        $definition = $container->getDefinition('notification.manager');

        $taggedServices = $container->findTaggedServiceIds('notification.extractor');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addExtractor', [new Reference($id)]);
        }
    }
}
