<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:45
 */

namespace Demroos\NotificationBundle\Tests\Manager;

use Demroos\NotificationBundle\Exception\NotificationException;
use Demroos\NotificationBundle\Manager\NotificationManager;
use Demroos\NotificationBundle\MetaExtractor\DefaultMetaExtractor;
use Demroos\NotificationBundle\Tests\BaseTestCase;
use Demroos\NotificationBundle\Tests\TestEntity;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class NotificationManagerTest extends BaseTestCase
{
    /** @var NotificationManager */
    protected $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->manager = new NotificationManager(
            Validation::createValidatorBuilder()
                ->enableAnnotationMapping()
                ->getValidator(),
            SerializerBuilder::create()->build()
        );
    }


    public function testHandleRequest()
    {
        $this->expectExceptionCode(500);
        $this->manager->handleRequest($this->createRequest([]));
    }

    public function testHandleRequestWithExtractorFail()
    {
        $this->expectExceptionCode(400);
        $data = [
            'entity' => 'test',
            'payload' => [

            ]
        ];
        $this->manager->addExtractor(new DefaultMetaExtractor(Validation::createValidator()));
        $this->manager->addEntity('test', TestEntity::class);
        $object = $this->manager->handleRequest($this->createRequest($data));

        $this->assertTrue($object instanceof TestEntity);
    }

    public function testHandleRequestWithExtractorSuccess()
    {
        $data = [
            'entity' => 'test',
            'payload' => [
                'id' => 2
            ]
        ];
        $this->manager->addExtractor(new DefaultMetaExtractor(Validation::createValidator()));
        $this->manager->addEntity('test', TestEntity::class);
        $object = $this->manager->handleRequest($this->createRequest($data));

        $this->assertTrue($object instanceof TestEntity);
        $this->assertEquals(2, $object->id);
    }

    public function testAddEntity()
    {
        $this->manager->addEntity('test', TestEntity::class);

        $expected = [
            'test' => TestEntity::class
        ];
        $this->assertEquals($expected, $this->getPropValue($this->manager, 'entities'));
    }

    public function testGetClassSuccess()
    {
        $this->manager->addEntity('test', TestEntity::class);

        $this->assertEquals(TestEntity::class, $this->getMethodValue($this->manager, 'getClass', ['test']));
    }

    public function testGetClassFail()
    {
        $this->expectExceptionCode(500);
        $this->expectException(NotificationException::class);
        $this->expectExceptionMessage('Not found class for entity -> test');
        $this->getMethodValue($this->manager, 'getClass', ['test']);
    }


    public function testAddExtractor()
    {
        $extractor = $this->createMock(DefaultMetaExtractor::class);
        $this->manager->addExtractor($extractor);

        $this->assertEquals([$extractor], $this->getPropValue($this->manager, 'extractors'));
    }
}
