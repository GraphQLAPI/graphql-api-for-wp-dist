<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class CustomPostSetFeaturedImageMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver|null
     */
    private $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\UnionType\CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver
     */
    public final function setCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver($customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver() : CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver
    {
        /** @var CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver */
        return $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver = $this->customPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getCustomPostSetFeaturedImageMutationErrorPayloadUnionTypeResolver();
    }
}
