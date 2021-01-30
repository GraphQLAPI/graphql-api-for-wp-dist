<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMediaMutations\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade;
use PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
class CustomPostMutationResolverHooks extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addFilter(\PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers::HOOK_UPDATE_SCHEMA_FIELD_ARGS, array($this, 'getSchemaFieldArgs'), 10, 3);
        $this->hooksAPI->addAction(\PoPSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE, array($this, 'setOrRemoveFeaturedImage'), 10, 2);
    }
    public function getSchemaFieldArgs(array $fieldArgs, \PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $fieldArgs[] = [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::FEATUREDIMAGE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($this->translationAPI->__('The ID of the featured image (of type %s)', 'custompost-mutations'), \PoPSchema\Media\TypeResolvers\MediaTypeResolver::NAME)];
        return $fieldArgs;
    }
    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     *
     * @param mixed $customPostID
     * @param mixed $form_data
     */
    public function setOrRemoveFeaturedImage($customPostID, $form_data) : void
    {
        $customPostMediaTypeAPI = \PoPSchema\CustomPostMediaMutations\Facades\CustomPostMediaTypeAPIFacade::getInstance();
        if (isset($form_data[\PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::FEATUREDIMAGE_ID])) {
            if ($featuredImageID = $form_data[\PoPSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties::FEATUREDIMAGE_ID]) {
                $customPostMediaTypeAPI->setFeaturedImage($customPostID, $featuredImageID);
            } else {
                $customPostMediaTypeAPI->removeFeaturedImage($customPostID);
            }
        }
    }
}
