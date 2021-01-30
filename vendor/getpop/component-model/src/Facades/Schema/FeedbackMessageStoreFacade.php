<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Facades\Schema;

use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class FeedbackMessageStoreFacade
{
    public static function getInstance() : \PoP\ComponentModel\Schema\FeedbackMessageStoreInterface
    {
        /**
         * @var FeedbackMessageStoreInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoP\ComponentModel\Schema\FeedbackMessageStoreInterface::class);
        return $service;
    }
}
