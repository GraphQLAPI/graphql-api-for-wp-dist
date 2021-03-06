<?php

declare (strict_types=1);
namespace PoPSchema\Tags\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
abstract class AbstractCustomPostListTagFieldResolver extends \PoPSchema\CustomPosts\FieldResolvers\AbstractCustomPostListFieldResolver
{
    use TagAPIRequestedContractTrait;
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['customPosts' => $translationAPI->__('Custom posts which contain this tag', 'pop-tags'), 'customPostCount' => $translationAPI->__('Number of custom posts which contain this tag', 'pop-tags')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    protected abstract function getQueryProperty() : string;
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
            case 'customPosts':
            case 'customPostCount':
                $query[$this->getQueryProperty()] = [$typeResolver->getID($tag)];
                break;
        }
        return $query;
    }
}
