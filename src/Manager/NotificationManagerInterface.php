<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:20
 */

namespace Demroos\NotificationBundle\Manager;

interface NotificationManagerInterface
{
    public function send($notification);
}
