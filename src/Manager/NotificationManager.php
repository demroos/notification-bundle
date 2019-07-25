<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 22.07.19
 * Time: 14:47
 */

namespace Demroos\NotificationBundle\Manager;

use Demroos\NotificationBundle\Exception\NotificationException;
use Demroos\NotificationBundle\MetaExtractor\MetaExtractorInterface;
use Demroos\NotificationBundle\MetaExtractor\MetaInfo;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class NotificationManager implements NotificationReceiverInterface, EntityCollectionInterface, ExtractorCollectionInterface
{
    /** @var ValidatorInterface */
    protected $validator;

    /** @var ArrayTransformerInterface */
    protected $transformer;

    /** @var MetaExtractorInterface[] */
    protected $extractors = [];

    protected $entities = [];

    /**
     * NotificationManager constructor.
     * @param ValidatorInterface $validator
     * @param ArrayTransformerInterface $transformer
     */
    public function __construct(ValidatorInterface $validator, ArrayTransformerInterface $transformer)
    {
        $this->validator = $validator;
        $this->transformer = $transformer;

        $this->entities = [];
    }

    public function addEntity($entity, $class)
    {
        $this->entities[$entity] = $class;
    }

    protected function createObject(MetaInfo $info)
    {
        $class = $this->getClass($info->getEntity());

        $object = $this->transformer->fromArray($info->getPayload(), $class);

        $errors = $this->validator->validate($object);
        $this->catchErrors($errors);

        return $object;
    }

    protected function getClass($type)
    {
        if (isset($this->entities[$type])) {
            return $this->entities[$type];
        } else {
            throw new NotificationException(sprintf('Not found class for entity -> %s', $type));
        }
    }

    /**
     * @param ConstraintViolationInterface[]|ConstraintViolationListInterface $errors
     */
    protected function catchErrors($errors)
    {
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = sprintf('%s -> %s', $error->getPropertyPath(), $error->getMessage());
                //$messages[] = $error->getMessage();
            }

            throw new NotificationException(
                implode('; ', $messages),
                400
            );
        }
    }

    public function handleRequest(Request $request)
    {
        foreach ($this->extractors as $extractor) {
            if ($result = $extractor->extract($request)) {
                return $this->createObject($result);
            }
        }

        throw new NotificationException('Not found extractor for this notification');
    }

    public function addExtractor(MetaExtractorInterface $extractor)
    {
        $this->extractors[] = $extractor;
    }
}
