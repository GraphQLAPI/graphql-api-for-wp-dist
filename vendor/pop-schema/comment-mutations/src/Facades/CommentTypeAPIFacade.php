<?php

declare (strict_types=1);
namespace PoPSchema\CommentMutations\Facades;

use PoPSchema\CommentMutations\TypeAPIs\CommentTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class CommentTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\CommentMutations\TypeAPIs\CommentTypeAPIInterface
    {
        /**
         * @var CommentTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\CommentMutations\TypeAPIs\CommentTypeAPIInterface::class);
        return $service;
    }
}
