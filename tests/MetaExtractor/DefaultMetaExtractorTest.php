<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:00
 */

namespace Demroos\NotificationBundle\Tests\MetaExtractor;

use Demroos\NotificationBundle\MetaExtractor\DefaultMetaExtractor;
use Demroos\NotificationBundle\Tests\BaseTestCase;
use Symfony\Component\Validator\Validation;

class DefaultMetaExtractorTest extends BaseTestCase
{
    /** @var DefaultMetaExtractor */
    protected $extractor;

    public function testExtractEmptyRequest()
    {
        $this->expectExceptionCode(400);
        $this->extractor->extract($this->createRequest([]));
    }

    public function testExtractFullRequest()
    {
        $data = ['entity' => 'test', 'payload' => ['id' => 1]];
        $meta = $this->extractor->extract($this->createRequest($data));
        $this->assertEquals($data['entity'], $meta->getEntity());
        $this->assertEquals($data['payload'], $meta->getPayload());
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->extractor = new DefaultMetaExtractor(Validation::createValidator());
    }
}
