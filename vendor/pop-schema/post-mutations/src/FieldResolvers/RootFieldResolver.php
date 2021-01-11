<?php

declare(strict_types=1);

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

class RootFieldResolver extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return array_merge([
            'createPost',
        ], !EngineComponentConfiguration::disableRedundantRootTypeMutationFields() ?
            [
                'updatePost',
            ] : []);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'createPost' => $translationAPI->__('Create a post', 'post-mutations'),
            'updatePost' => $translationAPI->__('Update a post', 'post-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'createPost' => SchemaDefinition::TYPE_ID,
            'updatePost' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'createPost':
                return SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs($typeResolver, $fieldName, false);
            case 'updatePost':
                return SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs($typeResolver, $fieldName, true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'createPost':
                return CreatePostMutationResolver::class;
            case 'updatePost':
                return UpdatePostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                return PostTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}