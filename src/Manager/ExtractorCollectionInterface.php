<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 18:30
 */

namespace Demroos\NotificationBundle\Manager;


use Demroos\NotificationBundle\MetaExtractor\MetaExtractorInterface;

interface ExtractorCollectionInterface
{
    public function addExtractor(MetaExtractorInterface $extractor);
}
