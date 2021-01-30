<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\Schema;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
class SchemaDefinitionHelpers
{
    public const HOOK_UPDATE_SCHEMA_FIELD_ARGS = __CLASS__ . ':update-schema-field-args';
    /**
     * @var mixed[]
     */
    private static $schemaFieldArgsCache = [];
    public static function getCreateUpdateCustomPostSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, bool $addCustomPostID) : array
    {
        $key = \get_class($typeResolver) . '-' . $fieldName;
        if (!isset(self::$schemaFieldArgsCache[$key])) {
            $hooksAPI = \PoP\Hooks\Facades\HooksAPIFacade::getInstance();
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
            /**
             * @var CustomPostStatusEnum
             */
            $customPostStatusEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostStatusEnum::class);
            $schemaFieldDefinition = \array_merge($addCustomPostID ? [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post to update', 'custompost-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]] : [], [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::TITLE, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The title of the custom post', 'custompost-mutations')], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::CONTENT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The content of the custom post', 'custompost-mutations')], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::STATUS, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The status of the custom post', 'custompost-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $customPostStatusEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($customPostStatusEnum->getValues())]]);
            self::$schemaFieldArgsCache[$key] = $hooksAPI->applyFilters(self::HOOK_UPDATE_SCHEMA_FIELD_ARGS, $schemaFieldDefinition, $typeResolver, $fieldName);
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
