# Notification Bundle

### Register Entity and Receive Notifications
Note: incoming notifications are set up by default to log to "notifications" channel

To work with notification receiver manager you will need a messenger bundle:
```bash
composer require symfony/messenger
```

Configure Symfony's messenger in file `config/packages/messenger.yaml`

Configure NotificationManager:
Add to config `notification.yml`
```yaml
notification:
  entities:
    - { name: entity_name, class: App\NotificationClass }

```

Change Sender class
```yaml
parameters:
    notification.sender.class: 'Class implement NotificationSenderInterface'
```

Create a controller class for your incoming notifications. 
The controller will handle ALL incoming notifications and dispatch a corresponding message using the above configuration.
```php
<?
/**
 * Class NotificationController
 * @package App\Controller\Api
 * @Route("/notification")
 */
class NotificationController extends ApiController
{
    /**
     * @Route("/endpoint", name="api_notification_endpoint", methods={"POST"})
     * @param Request $request
     * @param NotificationReceiverInterface $notificationManager
     * @return JsonResponse
     */
    public function handler(Request $request, NotificationReceiverInterface $notificationManager)
    {
        $notification = $notificationManager->handleRequest($request);

        $this->dispatchMessage($notification);

        return $this->responseBuilder->setData([])->getResponse();
    }
}
```

Then you need to handle a message that was previously dispatched by controller. 
For each entity there will be a different message handler.
For example:
```php
<? 
class UserNotificationHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * UserNotificationHandler constructor.
     * @param UserRepository $userRepository
     * @param ObjectManager $entityManager
     */
    public function __construct(UserRepository $userRepository, ObjectManager $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(UserNotification $notification)
    {
        $user = $this->userRepository->findOneBy(['oauthId' => $notification->id]);

        if (!$user instanceof User) {
            $user = new User();
            $user->setOauthId($notification->id);
        }

        $user
            ->setFirstName($notification->firstName)
            ->setLastName($notification->lastName)
            ->setRoles($notification->roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
```

### Send Notifications
Note: outgoing notifications are set up by default to log to "outgoing_notifications" channel


NotificationSender is a service that helps you send "notification" messages to RabbitMQ. It creates a formatted message 
with specific fields (in this case, entity ad payload fields) using provided Object (Entity) and JMS Array Transformer.
It is necessary to add Serializer\Groups to entity fields that must appear in notification. 
Group naming must follow this pattern: {{entityName}}.notification, e.g. `@JMS\Groups(groups={"user.notification"})`
Example message: 
```json
{
    "entity": "order",
    "payload": {
        "number": "TT1",
        "status": "ORDER_REGISTERED",
        ...
  }
}



``` 
In order to use NotificationSender you will need to install rabbit bundle: 
```bash
composer require php-amqplib/rabbitmq-bundle
```
Make sure you have ``config/packages/old_sound_rabbit_mq.yaml`` file with the following content:
```yaml
old_sound_rabbit_mq:
    connections:
        default:
            url: '%env(RABBITMQ_URL)%'
            lazy: true
    producers:
        # use 'old_sound_rabbit_mq.task_producer' service to send data.
        order:
            connection:       default
            exchange_options: { name: 'order', type: headers }
```

!!!Entity name and producer name should be the same!!!. If you wish to send an OrderNotification, you should use the config file example above for producer and set entity name to 'order'. 

To send a notification call:
```php
$notificationSender
    ->setEntityName('order')
    ->send($order, $id);
```

## Version Support

| Notification Bundle |  PHP  | Symfony      |
|---------------------|-------|--------------|
| [1.1] (develop)     | ^8.0  |  ^4.3\|^5.0  |
| [1.0] (1.0.x)       | ^7.3  |     ^4.3     |
