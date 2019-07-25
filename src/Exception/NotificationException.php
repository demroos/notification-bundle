<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 22.07.19
 * Time: 16:10
 */

namespace Demroos\NotificationBundle\Exception;

use Throwable;

class NotificationException extends \RuntimeException
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
