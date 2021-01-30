<?php

declare (strict_types=1);
namespace PoPSchema\PostMutations\FieldResolvers;

use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
class RootFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoP\Engine\TypeResolvers\RootTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return \array_merge(['createPost'], !\PoP\Engine\ComponentConfiguration::disableRedundantRootTypeMutationFields() ? ['updatePost'] : []);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['createPost' => $translationAPI->__('Create a post', 'post-mutations'), 'updatePost' => $translationAPI->__('Update a post', 'post-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['createPost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'updatePost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        switch ($fieldName) {
            case 'createPost':
                return \PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs($typeResolver, $fieldName, \false);
            case 'updatePost':
                return \PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs($typeResolver, $fieldName, \true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'createPost':
                return \PoPSchema\PostMutations\MutationResolvers\CreatePostMutationResolver::class;
            case 'updatePost':
                return \PoPSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                return \PoPSchema\Posts\TypeResolvers\PostTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
