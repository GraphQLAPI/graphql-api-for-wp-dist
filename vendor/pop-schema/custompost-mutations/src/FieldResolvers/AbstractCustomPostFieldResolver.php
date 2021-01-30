<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
abstract class AbstractCustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getFieldNamesToResolve() : array
    {
        return ['update'];
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['update' => $translationAPI->__('Update the custom post', 'custompost-mutations')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['update' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        switch ($fieldName) {
            case 'update':
                return \PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs($typeResolver, $fieldName, \false);
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
            case 'update':
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
        $post = $resultItem;
        switch ($fieldName) {
            case 'update':
                $fieldArgs[\PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties::ID] = $typeResolver->getID($post);
                break;
        }
        return $fieldArgs;
    }
}
