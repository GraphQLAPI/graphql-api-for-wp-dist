<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class RootCreatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootCreatePostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootCreatePostMutationErrorPayloadUnionTypeResolver $rootCreatePostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootCreatePostMutationErrorPayloadUnionTypeResolver($rootCreatePostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootCreatePostMutationErrorPayloadUnionTypeResolver = $rootCreatePostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootCreatePostMutationErrorPayloadUnionTypeResolver() : RootCreatePostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootCreatePostMutationErrorPayloadUnionTypeResolver */
        return $this->rootCreatePostMutationErrorPayloadUnionTypeResolver = $this->rootCreatePostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootCreatePostMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootCreatePostMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getRootCreatePostMutationErrorPayloadUnionTypeResolver();
    }
}
