<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\PostSetTagsMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class PostSetTagsMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver|null
     */
    private $postSetTagsMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostTagMutations\TypeResolvers\UnionType\PostSetTagsMutationErrorPayloadUnionTypeResolver $postSetTagsMutationErrorPayloadUnionTypeResolver
     */
    public final function setPostSetTagsMutationErrorPayloadUnionTypeResolver($postSetTagsMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->postSetTagsMutationErrorPayloadUnionTypeResolver = $postSetTagsMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getPostSetTagsMutationErrorPayloadUnionTypeResolver() : PostSetTagsMutationErrorPayloadUnionTypeResolver
    {
        /** @var PostSetTagsMutationErrorPayloadUnionTypeResolver */
        return $this->postSetTagsMutationErrorPayloadUnionTypeResolver = $this->postSetTagsMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(PostSetTagsMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [PostSetTagsMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getPostSetTagsMutationErrorPayloadUnionTypeResolver();
    }
}
