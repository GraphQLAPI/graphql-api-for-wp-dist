<?php

declare (strict_types=1);
namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;
class CustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['addComment'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['addComment' => $translationAPI->__('Add a comment to the custom post', 'comment-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['addComment' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        switch ($fieldName) {
            case 'addComment':
                return \PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, \false, \true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }
    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'addComment':
                return \true;
        }
        return parent::validateMutationOnResultItem($typeResolver, $fieldName);
    }
    /**
     * @param object $resultItem
     */
    protected function getFieldArgsToExecuteMutation(array $fieldArgs, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName) : array
    {
        $fieldArgs = parent::getFieldArgsToExecuteMutation($fieldArgs, $typeResolver, $resultItem, $fieldName);
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'addComment':
                $fieldArgs[\PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }
        return $fieldArgs;
    }
    public function resolveFieldMutationResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return \PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver::class;
        }
        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return \PoPSchema\Comments\TypeResolvers\CommentTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
