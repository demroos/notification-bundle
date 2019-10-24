<?php


namespace Demroos\NotificationBundle\Sender;

use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\SerializationContext;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationSender implements NotificationSenderInterface, LoggerAwareInterface
{
    private $entityName;
    /**
     * @var ArrayTransformerInterface
     */
    private $transformer;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * NotificationSender constructor.
     * @param ArrayTransformerInterface $transformer
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ArrayTransformerInterface $transformer, ContainerInterface $container, LoggerInterface $logger)
    {
        $this->transformer = $transformer;
        $this->container = $container;
        $this->logger = $logger;
    }

    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function createNotification($entity)
    {
        /** @var SerializationContext $context */
        $context = SerializationContext::create()
            ->setGroups([sprintf('%s.notification', $this->entityName)]);
        $payload = $this->transformer->toArray($entity, $context);

        $notification = [
            'entity' => $this->entityName,
            'payload' => $payload
        ];

        return json_encode($notification);
    }

    public function send($entity, $id)
    {
        try {
            $notification = $this->createNotification($entity);
            $this->logger->debug($notification);

            $producer = $this->container->get(sprintf("old_sound_rabbit_mq.%s_producer", $this->entityName));

            $producer->publish(
                $notification,
                sprintf("%s.%n", $this->entityName, $id),
                [],
                [
                    'sub.all' => true, // todo refactor
                    'entity_id' => $id
                ]
            );

            $this->logger->info(sprintf('Notification sent, name: %s, id: %s', $this->entityName, $id));
        } catch (\Throwable $e) {
            $this->logger->critical(
                $e->getMessage(),
                [
                    'user' => $id
                ]
            );
        }    }

    /**
     * @param mixed $entityName
     * @return NotificationSender
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
        return $this;
    }
}
