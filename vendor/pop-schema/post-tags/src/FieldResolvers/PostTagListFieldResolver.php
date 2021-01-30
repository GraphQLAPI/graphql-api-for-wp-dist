<?php

declare (strict_types=1);
namespace PoPSchema\PostTags\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
class PostTagListFieldResolver extends \PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver::class);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['posts' => $translationAPI->__('Posts which contain this tag', 'pop-taxonomies'), 'postCount' => $translationAPI->__('Number of posts which contain this tag', 'pop-taxonomies')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @return array<string, mixed>
     * @param object $resultItem
     */
    protected function getQuery(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = []) : array
    {
        $query = parent::getQuery($typeResolver, $resultItem, $fieldName, $fieldArgs);
        $tag = $resultItem;
        switch ($fieldName) {
            case 'posts':
            case 'postCount':
                $query['tag-ids'] = [$typeResolver->getID($tag)];
                break;
        }
        return $query;
    }
}
