<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class RootUpdatePostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\UnionType\RootUpdatePostMutationErrorPayloadUnionTypeResolver $rootUpdatePostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootUpdatePostMutationErrorPayloadUnionTypeResolver($rootUpdatePostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $rootUpdatePostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootUpdatePostMutationErrorPayloadUnionTypeResolver() : RootUpdatePostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootUpdatePostMutationErrorPayloadUnionTypeResolver */
        return $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver = $this->rootUpdatePostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootUpdatePostMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootUpdatePostMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getRootUpdatePostMutationErrorPayloadUnionTypeResolver();
    }
}
