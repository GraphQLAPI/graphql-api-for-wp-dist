<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\CustomPosts\FieldResolvers;

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
class CustomPostListUserFieldResolver extends \PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Users\TypeResolvers\UserTypeResolver::class);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['customPosts' => $translationAPI->__('Custom posts by the user', 'pop-users'), 'customPostCount' => $translationAPI->__('Number of custom posts by the user', 'pop-users')];
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
        $user = $resultItem;
        switch ($fieldName) {
            case 'customPosts':
            case 'customPostCount':
                $query['authors'] = [$typeResolver->getID($user)];
                break;
        }
        return $query;
    }
}
