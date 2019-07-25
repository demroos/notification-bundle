<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:27
 */

namespace Demroos\NotificationBundle\Manager;

use Symfony\Component\HttpFoundation\Request;

interface NotificationReceiverInterface
{
    public function handleRequest(Request $request);
}
