<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 13.04.2021
 * Time: 18:26
 */

namespace Demroos\NotificationBundle\Tests\DependencyInjection;

use Demroos\NotificationBundle\DependencyInjection\NotificationExtension;
use Demroos\NotificationBundle\NotificationBundle;
use Demroos\NotificationBundle\Sender\RabbitNotificationSender;
use Demroos\NotificationBundle\Tests\TestSender;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NotificationExtensionTest extends TestCase
{
    public function testDefaultConfig()
    {
        $config = [
            'notification' => []
        ];

        $container = $this->getContainerForConfigLoad($config);

        $senderDef = $container->getDefinition('notification.sender');

        $this->assertEquals('%notification.sender.class%', $senderDef->getClass());
        $this->assertEquals(RabbitNotificationSender::class, $container->getParameter('notification.sender.class'));
    }

    public function testCustomSenderConfig()
    {
        $config = [
            'notification' => [
                'sender' => TestSender::class
            ]
        ];

        $container = $this->getContainerForConfigLoad($config);

        $senderDef = $container->getDefinition('notification.sender');

        $this->assertEquals(TestSender::class, $senderDef->getClass());
        $this->assertEquals(RabbitNotificationSender::class, $container->getParameter('notification.sender.class'));
    }


    private function getContainerForConfigLoad($config): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $bundle = new NotificationBundle();
        $extension = $bundle->getContainerExtension();
        $extension->load($config, $container);

        return $container;
    }
}
