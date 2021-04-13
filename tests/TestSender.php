<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 13.04.2021
 * Time: 18:30
 */

namespace Demroos\NotificationBundle\Tests;

use Demroos\NotificationBundle\Sender\NotificationSenderInterface;

class TestSender implements NotificationSenderInterface
{

    public function send($entityName, $entity, $id)
    {
    }
}
