<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\PostUpdateMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class PostUpdateMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver|null
     */
    private $postUpdateMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\PostUpdateMutationErrorPayloadUnionTypeResolver $postUpdateMutationErrorPayloadUnionTypeResolver
     */
    public final function setPostUpdateMutationErrorPayloadUnionTypeResolver($postUpdateMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->postUpdateMutationErrorPayloadUnionTypeResolver = $postUpdateMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getPostUpdateMutationErrorPayloadUnionTypeResolver() : PostUpdateMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostUpdateMutationErrorPayloadUnionTypeResolver */
        return $this->postUpdateMutationErrorPayloadUnionTypeResolver = $this->postUpdateMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(PostUpdateMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PostUpdateMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getPostUpdateMutationErrorPayloadUnionTypeResolver();
    }
}
