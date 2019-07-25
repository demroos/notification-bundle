<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:25
 */

namespace Demroos\NotificationBundle\Manager;

interface EntityCollectionInterface
{
    public function addEntity($entity, $class);
}
