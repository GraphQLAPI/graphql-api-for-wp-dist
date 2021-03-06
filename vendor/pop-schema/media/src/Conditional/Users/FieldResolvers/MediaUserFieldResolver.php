<?php

declare (strict_types=1);
namespace PoPSchema\Media\Conditional\Users\FieldResolvers;

use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class MediaUserFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Media\TypeResolvers\MediaTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['author'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['author' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['author' => $translationAPI->__('Media element\'s author', 'pop-media')];
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
        $cmsmediaapi = \PoPSchema\Media\FunctionAPIFactory::getInstance();
        $media = $resultItem;
        switch ($fieldName) {
            case 'author':
                return $cmsmediaapi->getMediaAuthorId($media);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                return \PoPSchema\Users\TypeResolvers\UserTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
