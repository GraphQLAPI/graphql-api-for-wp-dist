<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CommentReplyMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class CommentReplyMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver|null
     */
    private $commentReplyMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CommentReplyMutationErrorPayloadUnionTypeResolver $commentReplyMutationErrorPayloadUnionTypeResolver
     */
    public final function setCommentReplyMutationErrorPayloadUnionTypeResolver($commentReplyMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->commentReplyMutationErrorPayloadUnionTypeResolver = $commentReplyMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCommentReplyMutationErrorPayloadUnionTypeResolver() : CommentReplyMutationErrorPayloadUnionTypeResolver
    {
        /** @var CommentReplyMutationErrorPayloadUnionTypeResolver */
        return $this->commentReplyMutationErrorPayloadUnionTypeResolver = $this->commentReplyMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CommentReplyMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CommentReplyMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getCommentReplyMutationErrorPayloadUnionTypeResolver();
    }
}
