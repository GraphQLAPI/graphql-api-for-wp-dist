<?php

declare (strict_types=1);
namespace PoPSchema\Tags\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;
abstract class AbstractTagFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    use TagAPIRequestedContractTrait;
    public static function getImplementedInterfaceClasses() : array
    {
        return [\PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver::class];
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['url', 'name', 'slug', 'description', 'parent', 'count'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['url' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_URL, 'name' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'slug' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'description' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'parent' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'count' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['url' => $translationAPI->__('Tag URL', 'pop-tags'), 'name' => $translationAPI->__('Tag', 'pop-tags'), 'slug' => $translationAPI->__('Tag slug', 'pop-tags'), 'description' => $translationAPI->__('Tag description', 'pop-tags'), 'parent' => $translationAPI->__('Parent category (if this category is a child of another one)', 'pop-tags'), 'count' => $translationAPI->__('Number of custom posts containing this tag', 'pop-tags')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $cmstagsresolver = $this->getObjectPropertyAPI();
        $tagapi = $this->getTypeAPI();
        $tag = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $tagapi->getTagLink($typeResolver->getID($tag));
            case 'name':
                return $cmstagsresolver->getTagName($tag);
            case 'slug':
                return $cmstagsresolver->getTagSlug($tag);
            case 'description':
                return $cmstagsresolver->getTagDescription($tag);
            case 'parent':
                return $cmstagsresolver->getTagParent($tag);
            case 'count':
                return $cmstagsresolver->getTagCount($tag);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'parent':
                return $this->getTypeResolverClass();
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
