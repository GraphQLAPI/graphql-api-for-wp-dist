<?php

declare (strict_types=1);
namespace PoPSchema\Comments\Facades;

use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CommentTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface::class);
        return $service;
    }
}
