<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostTagMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;
abstract class AbstractSetTagsOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $customPostID = $form_data[\PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID];
        $postTags = $form_data[\PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties::TAGS];
        $append = $form_data[\PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties::APPEND];
        $customPostTagTypeAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeAPI->setTags($customPostID, $postTags, $append);
        return $customPostID;
    }
    protected abstract function getCustomPostTagTypeMutationAPI() : CustomPostTagTypeMutationAPIInterface;
    public function validateErrors(array $form_data) : ?array
    {
        $errors = [];
        // Check that the user is logged-in
        $this->validateUserIsLoggedIn($errors);
        if ($errors) {
            return $errors;
        }
        if (!$form_data[\PoPSchema\CustomPostTagMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID]) {
            $errors[] = \sprintf($this->translationAPI->__('The %s ID is missing.', 'custompost-tag-mutations'), $this->getEntityName());
        }
        return $errors;
    }
    protected function getEntityName() : string
    {
        return $this->translationAPI->__('custom post', 'custompost-tag-mutations');
    }
}
