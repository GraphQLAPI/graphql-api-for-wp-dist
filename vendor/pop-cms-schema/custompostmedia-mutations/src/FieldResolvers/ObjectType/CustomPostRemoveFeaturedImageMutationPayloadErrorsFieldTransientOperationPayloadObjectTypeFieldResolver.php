<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class CustomPostRemoveFeaturedImageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver($customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver() : CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver */
        return $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->customPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getCustomPostRemoveFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}
