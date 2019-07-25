<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 25.07.2019
 * Time: 13:47
 */
use Doctrine\Common\Annotations\AnnotationRegistry; //Только если вы используете Doctrine и анотации
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass')); //Только если вы используете Doctrine и аннотации

return $loader;
