<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 17:08
 */

namespace Demroos\NotificationBundle\Entity;


interface NotificationEntityInterface
{
    public function getEntityName(): string;
}
