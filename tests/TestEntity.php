<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:54
 */

namespace Demroos\NotificationBundle\Tests;

use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class TestEntity
{
    /**
     * @Serializer\Type(name="integer")
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public $id;
}
