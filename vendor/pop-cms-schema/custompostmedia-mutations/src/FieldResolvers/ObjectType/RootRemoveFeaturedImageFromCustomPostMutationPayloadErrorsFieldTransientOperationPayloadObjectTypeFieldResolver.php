<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class RootRemoveFeaturedImageFromCustomPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver($rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver() : RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver = $this->rootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getRootRemoveFeaturedImageFromCustomPostMutationErrorPayloadUnionTypeResolver();
    }
}
