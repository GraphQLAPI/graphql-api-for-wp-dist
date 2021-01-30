<?php

declare (strict_types=1);
namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
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
        return ['addCommentToCustomPost', 'replyComment'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['addCommentToCustomPost' => $translationAPI->__('Add a comment to a custom post', 'comment-mutations'), 'replyComment' => $translationAPI->__('Reply a comment with another comment', 'comment-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['addCommentToCustomPost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'replyComment' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
                return \PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, \true, \true);
            case 'replyComment':
                return \PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, \false, \true, \true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return \PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return \PoPSchema\Comments\TypeResolvers\CommentTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
