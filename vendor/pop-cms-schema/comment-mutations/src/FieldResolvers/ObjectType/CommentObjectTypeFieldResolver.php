<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentReplyFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
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
class CommentObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver|null
     */
    private $addCommentToCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentReplyFilterInputObjectTypeResolver|null
     */
    private $commentReplyFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver|null
     */
    private $commentReplyMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver|null
     */
    private $payloadableAddCommentToCustomPostMutationResolver;
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
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\CommentReplyFilterInputObjectTypeResolver $commentReplyFilterInputObjectTypeResolver
     */
    public final function setCommentReplyFilterInputObjectTypeResolver($commentReplyFilterInputObjectTypeResolver) : void
    {
        $this->commentReplyFilterInputObjectTypeResolver = $commentReplyFilterInputObjectTypeResolver;
    }
    protected final function getCommentReplyFilterInputObjectTypeResolver() : CommentReplyFilterInputObjectTypeResolver
    {
        /** @var CommentReplyFilterInputObjectTypeResolver */
        return $this->commentReplyFilterInputObjectTypeResolver = $this->commentReplyFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentReplyFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver $commentReplyMutationPayloadObjectTypeResolver
     */
    public final function setCommentReplyMutationPayloadObjectTypeResolver($commentReplyMutationPayloadObjectTypeResolver) : void
    {
        $this->commentReplyMutationPayloadObjectTypeResolver = $commentReplyMutationPayloadObjectTypeResolver;
    }
    protected final function getCommentReplyMutationPayloadObjectTypeResolver() : CommentReplyMutationPayloadObjectTypeResolver
    {
        /** @var CommentReplyMutationPayloadObjectTypeResolver */
        return $this->commentReplyMutationPayloadObjectTypeResolver = $this->commentReplyMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CommentReplyMutationPayloadObjectTypeResolver::class);
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
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CommentObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['reply'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'reply':
                return $this->__('Reply a comment with another comment', 'comment-mutations');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
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
            case 'reply':
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
            case 'reply':
                return [MutationInputProperties::INPUT => $this->getCommentReplyFilterInputObjectTypeResolver()];
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
            case ['reply' => MutationInputProperties::INPUT]:
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
            case 'reply':
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
        $comment = $object;
        switch ($field->getName()) {
            case 'reply':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $this->getCommentTypeAPI()->getCommentPostID($comment);
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::PARENT_COMMENT_ID} = $objectTypeResolver->getID($comment);
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
            case 'reply':
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
            case 'reply':
                return $usePayloadableCommentMutations ? $this->getCommentReplyMutationPayloadObjectTypeResolver() : $this->getCommentObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
