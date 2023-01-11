<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\PostCategoryMutations\TypeResolvers\ObjectType\RootSetCategoriesOnPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class RootSetCategoriesOnPostMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver|null
     */
    private $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    /**
     * @param \PoPCMSSchema\PostCategoryMutations\TypeResolvers\UnionType\RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver
     */
    public final function setRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver($rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver) : void
    {
        $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver;
    }
    protected final function getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver() : RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver
    {
        /** @var RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver */
        return $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver = $this->rootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver ?? $this->instanceManager->getInstance(RootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootSetCategoriesOnPostMutationPayloadObjectTypeResolver::class];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    protected function getErrorsFieldFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        return $this->getRootSetCategoriesOnPostMutationErrorPayloadUnionTypeResolver();
    }
}
