<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 16:31
 */

namespace Demroos\NotificationBundle;

use Demroos\NotificationBundle\DependencyInjection\Compiler\NotificationExtractorCollectorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NotificationBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NotificationExtractorCollectorPass());
    }

}
