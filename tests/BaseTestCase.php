<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:50
 */

namespace Demroos\NotificationBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseTestCase extends TestCase
{

    protected function createRequest($data)
    {
        $mock = $this->createMock(Request::class);

        $mock->method('getContent')->willReturn(json_encode($data));

        return $mock;
    }

    /**
     * @param $object
     * @param $propName
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getPropValue($object, $propName)
    {
        $ref = new \ReflectionClass($object);
        $refProp = $ref->getProperty($propName);
        $refProp->setAccessible(true);
        return $refProp->getValue($object);
    }

    /**
     * @param $object
     * @param string $methodName
     * @param array $options
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getMethodValue($object, string $methodName, array $options = [])
    {
        $ref = new \ReflectionClass($object);
        $refMethod = $ref->getMethod($methodName);
        $refMethod->setAccessible(true);
        return $refMethod->invokeArgs($object, $options);
    }
}
