<?php

declare (strict_types=1);
namespace PoPSchema\Posts\FieldResolvers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\Types\Status;
class PostLegacyContentFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\Posts\TypeResolvers\PostTypeResolver::class];
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['isPublished'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['isPublished' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        $nonNullableFieldNames = ['isPublished'];
        if (\in_array($fieldName, $nonNullableFieldNames)) {
            return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['isPublished' => $translationAPI->__('Has the post been published?', 'content')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldDeprecationDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $placeholder_status = $translationAPI->__('Use \'isStatus(status:%s)\' instead of \'%s\'', 'content');
        $descriptions = ['isPublished' => \sprintf($placeholder_status, \PoPSchema\CustomPosts\Types\Status::PUBLISHED, $fieldName)];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDeprecationDescription($typeResolver, $fieldName, $fieldArgs);
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
        $customPostTypeAPI = \PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'isPublished':
                return \PoPSchema\CustomPosts\Types\Status::PUBLISHED == $customPostTypeAPI->getStatus($post);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
