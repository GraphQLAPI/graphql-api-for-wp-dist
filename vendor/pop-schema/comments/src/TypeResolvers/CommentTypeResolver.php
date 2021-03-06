<?php

declare (strict_types=1);
namespace PoPSchema\Comments\TypeResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Comments\TypeDataLoaders\CommentTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
class CommentTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver
{
    public const NAME = 'Comment';
    public function getTypeName() : string
    {
        return self::NAME;
    }
    public function getSchemaTypeDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Comments added to posts', 'comments');
    }
    /**
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $cmscommentsresolver = \PoPSchema\Comments\ObjectPropertyResolverFactory::getInstance();
        $comment = $resultItem;
        return $cmscommentsresolver->getCommentId($comment);
    }
    public function getTypeDataLoaderClass() : string
    {
        return \PoPSchema\Comments\TypeDataLoaders\CommentTypeDataLoader::class;
    }
}
