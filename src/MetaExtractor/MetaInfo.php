<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 17:26
 */

namespace Demroos\NotificationBundle\MetaExtractor;

class MetaInfo
{
    /** @var string */
    protected $entity;
    /** @var array */
    protected $payload;

    /**
     * MetaInfo constructor.
     * @param string $entity
     * @param array $payload
     */
    public function __construct(string $entity, array $payload)
    {
        $this->entity = $entity;
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    public function getEntity(): string
    {
        return $this->entity;
    }

    /**
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }


}
