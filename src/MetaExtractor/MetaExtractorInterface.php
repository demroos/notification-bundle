<?php
/**
 * Created by PhpStorm.
 * @author Ewgeniy Kiselev <demroos@gmail.com>
 * Date: 23.07.19
 * Time: 17:24
 */

namespace Demroos\NotificationBundle\MetaExtractor;

use Symfony\Component\HttpFoundation\Request;

interface MetaExtractorInterface
{
    public function extract(Request $request): MetaInfo;
}
