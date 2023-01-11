<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class RootReplyCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\RootReplyCommentMutationErrorPayloadUnionTypeResolver $rootReplyCommentMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootReplyCommentMutationErrorPayloadUnionTypeResolver($rootReplyCommentMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $rootReplyCommentMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootReplyCommentMutationErrorPayloadUnionTypeResolver() : RootReplyCommentMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootReplyCommentMutationErrorPayloadUnionTypeResolver */
        return $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver = $this->rootReplyCommentMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootReplyCommentMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootReplyCommentMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getRootReplyCommentMutationErrorPayloadUnionTypeResolver();
    }
}
