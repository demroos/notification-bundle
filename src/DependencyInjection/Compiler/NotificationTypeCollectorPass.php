<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <evgeniy.kiselev@pingdelivery.com>
 * Date: 22.07.19
 * Time: 17:48
 */

namespace Demroos\NotificationBundle\DependencyInjection\Compiler;

use Demroos\NotificationBundle\Manager\NotificationManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NotificationTypeCollectorPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('notification.manager')) {
            return;
        }

        $definition = $container->getDefinition('notification.manager');

        $taggedServices = $container->findTaggedServiceIds('notification.entity');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $tag => $attr) {
                $definition->addMethodCall('addEntity', [
                    new Reference($id),
                    $attr['entity']
                ]);
            }
        }

    }
}
