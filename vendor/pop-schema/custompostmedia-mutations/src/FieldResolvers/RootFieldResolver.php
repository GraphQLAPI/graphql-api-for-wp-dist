<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
class RootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        if (\PoP\Engine\ComponentConfiguration::disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return ['setFeaturedImageOnCustomPost', 'removeFeaturedImageFromCustomPost'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['setFeaturedImageOnCustomPost' => $translationAPI->__('Set the featured image on a custom post', 'custompostmedia-mutations'), 'removeFeaturedImageFromCustomPost' => $translationAPI->__('Remove the featured image from a custom post', 'custompostmedia-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['setFeaturedImageOnCustomPost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'removeFeaturedImageFromCustomPost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $setRemoveFeaturedImageSchemaFieldArgs = [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post', 'custompostmedia-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]];
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return \array_merge($setRemoveFeaturedImageSchemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::MEDIA_ITEM_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('The ID of the featured image, of type \'%s\'', 'custompostmedia-mutations'), \PoPSchema\Media\TypeResolvers\MediaTypeResolver::NAME), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'removeFeaturedImageFromCustomPost':
                return $setRemoveFeaturedImageSchemaFieldArgs;
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return \PoPSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver::class;
            case 'removeFeaturedImageFromCustomPost':
                return \PoPSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageOnCustomPostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return \PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
