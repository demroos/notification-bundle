# Notification Bundle

### Register Entity

Add tag to service
```yaml
services:
    your_entity:
      tags: { name: notification.entity, entity: entity_name}

```

Impliment interface `NotificationEntityInterface`
```php
<?php

use \Demroos\NotificationBundle\Entity\NotificationEntityInterface;

class YourEntity implements NotificationEntityInterface {
    
    public function getEntityName(): string
    {
        return 'entity_name';
    }
    
}
```
