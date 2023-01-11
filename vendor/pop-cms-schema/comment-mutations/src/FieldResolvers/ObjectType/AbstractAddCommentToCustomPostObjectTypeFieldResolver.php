<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CustomPostAddCommentFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\FieldResolvers\ObjectType\MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
abstract class AbstractAddCommentToCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeCommentableCustomPostObjectTypeFieldResolverTrait;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver|null
     */
    private $addCommentToCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CustomPostAddCommentFilterInputObjectTypeResolver|null
     */
    private $customPostAddCommentFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver|null
     */
    private $customPostAddCommentMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver|null
     */
    private $payloadableAddCommentToCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver $commentObjectTypeResolver
     */
    public final function setCommentObjectTypeResolver($commentObjectTypeResolver) : void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    protected final function getCommentObjectTypeResolver() : CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver = $this->commentObjectTypeResolver ?? $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver
     */
    public final function setAddCommentToCustomPostMutationResolver($addCommentToCustomPostMutationResolver) : void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    protected final function getAddCommentToCustomPostMutationResolver() : AddCommentToCustomPostMutationResolver
    {
        /** @var AddCommentToCustomPostMutationResolver */
        return $this->addCommentToCustomPostMutationResolver = $this->addCommentToCustomPostMutationResolver ?? $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CustomPostAddCommentFilterInputObjectTypeResolver $customPostAddCommentFilterInputObjectTypeResolver
     */
    public final function setCustomPostAddCommentFilterInputObjectTypeResolver($customPostAddCommentFilterInputObjectTypeResolver) : void
    {
        $this->customPostAddCommentFilterInputObjectTypeResolver = $customPostAddCommentFilterInputObjectTypeResolver;
    }
    protected final function getCustomPostAddCommentFilterInputObjectTypeResolver() : CustomPostAddCommentFilterInputObjectTypeResolver
    {
        /** @var CustomPostAddCommentFilterInputObjectTypeResolver */
        return $this->customPostAddCommentFilterInputObjectTypeResolver = $this->customPostAddCommentFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostAddCommentFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver $customPostAddCommentMutationPayloadObjectTypeResolver
     */
    public final function setCustomPostAddCommentMutationPayloadObjectTypeResolver($customPostAddCommentMutationPayloadObjectTypeResolver) : void
    {
        $this->customPostAddCommentMutationPayloadObjectTypeResolver = $customPostAddCommentMutationPayloadObjectTypeResolver;
    }
    protected final function getCustomPostAddCommentMutationPayloadObjectTypeResolver() : CustomPostAddCommentMutationPayloadObjectTypeResolver
    {
        /** @var CustomPostAddCommentMutationPayloadObjectTypeResolver */
        return $this->customPostAddCommentMutationPayloadObjectTypeResolver = $this->customPostAddCommentMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostAddCommentMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver
     */
    public final function setPayloadableAddCommentToCustomPostMutationResolver($payloadableAddCommentToCustomPostMutationResolver) : void
    {
        $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
    }
    protected final function getPayloadableAddCommentToCustomPostMutationResolver() : PayloadableAddCommentToCustomPostMutationResolver
    {
        /** @var PayloadableAddCommentToCustomPostMutationResolver */
        return $this->payloadableAddCommentToCustomPostMutationResolver = $this->payloadableAddCommentToCustomPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface $commentTypeAPI
     */
    public final function setCommentTypeAPI($commentTypeAPI) : void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    protected final function getCommentTypeAPI() : CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI = $this->commentTypeAPI ?? $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['addComment'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return $this->getAddCommentFieldDescription();
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    public function getAddCommentFieldDescription() : string
    {
        return $this->__('Add a comment to the custom post', 'comment-mutations');
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if (!$usePayloadableCommentMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        switch ($fieldName) {
            case 'addComment':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'addComment':
                return [MutationInputProperties::INPUT => $this->getCustomPostAddCommentFilterInputObjectTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['addComment' => MutationInputProperties::INPUT]:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function validateMutationOnObject($objectTypeResolver, $fieldName) : bool
    {
        switch ($fieldName) {
            case 'addComment':
                return \true;
            default:
                return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object) : array
    {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object);
        $customPost = $object;
        switch ($field->getName()) {
            case 'addComment':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldMutationResolver($objectTypeResolver, $fieldName) : ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        switch ($fieldName) {
            case 'addComment':
                return $usePayloadableCommentMutations ? $this->getPayloadableAddCommentToCustomPostMutationResolver() : $this->getAddCommentToCustomPostMutationResolver();
            default:
                return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        switch ($fieldName) {
            case 'addComment':
                return $usePayloadableCommentMutations ? $this->getCustomPostAddCommentMutationPayloadObjectTypeResolver() : $this->getCommentObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
