<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\CustomPostAddCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class CustomPostAddCommentMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\UnionType\CustomPostAddCommentMutationErrorPayloadUnionTypeResolver $customPostAddCommentMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostAddCommentMutationErrorPayloadUnionTypeResolver($customPostAddCommentMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $customPostAddCommentMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver() : CustomPostAddCommentMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostAddCommentMutationErrorPayloadUnionTypeResolver */
        return $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver = $this->customPostAddCommentMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostAddCommentMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CustomPostAddCommentMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getCustomPostAddCommentMutationErrorPayloadUnionTypeResolver();
    }
}
