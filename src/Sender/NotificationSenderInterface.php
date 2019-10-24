<?php


namespace Demroos\NotificationBundle\Sender;

interface NotificationSenderInterface
{
    public function send($entityName, $entity, $id);
}
