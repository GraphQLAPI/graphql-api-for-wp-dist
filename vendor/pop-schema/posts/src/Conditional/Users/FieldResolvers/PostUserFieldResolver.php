<?php

declare (strict_types=1);
namespace PoPSchema\Posts\Conditional\Users\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
class PostUserFieldResolver extends \PoPSchema\Posts\FieldResolvers\AbstractPostFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Users\TypeResolvers\UserTypeResolver::class);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['posts' => $translationAPI->__('Posts by the user', 'users'), 'postCount' => $translationAPI->__('Number of posts by the user', 'users')];
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
            case 'posts':
            case 'postCount':
                $query['authors'] = [$typeResolver->getID($user)];
                break;
        }
        return $query;
    }
}
