<?php

declare (strict_types=1);
namespace PoPCMSSchema\Meta\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPCMSSchema\Meta\FeedbackItemProviders\FeedbackItemProvider;
use PoPCMSSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
abstract class AbstractWithMetaObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver|null
     */
    private $withMetaInterfaceTypeFieldResolver;
    /**
     * @param \PoPCMSSchema\Meta\FieldResolvers\InterfaceType\WithMetaInterfaceTypeFieldResolver $withMetaInterfaceTypeFieldResolver
     */
    public final function setWithMetaInterfaceTypeFieldResolver($withMetaInterfaceTypeFieldResolver) : void
    {
        $this->withMetaInterfaceTypeFieldResolver = $withMetaInterfaceTypeFieldResolver;
    }
    protected final function getWithMetaInterfaceTypeFieldResolver() : WithMetaInterfaceTypeFieldResolver
    {
        /** @var WithMetaInterfaceTypeFieldResolver */
        return $this->withMetaInterfaceTypeFieldResolver = $this->withMetaInterfaceTypeFieldResolver ?? $this->instanceManager->getInstance(WithMetaInterfaceTypeFieldResolver::class);
    }
    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers() : array
    {
        return [$this->getWithMetaInterfaceTypeFieldResolver()];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['metaValue', 'metaValues'];
    }
    protected abstract function getMetaTypeAPI() : MetaTypeAPIInterface;
    /**
     * Custom validations
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validateFieldKeyValues($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
        parent::validateFieldKeyValues($objectTypeResolver, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'metaValue':
            case 'metaValues':
                if (!$this->getMetaTypeAPI()->validateIsMetaKeyAllowed($fieldDataAccessor->getValue('key'))) {
                    $field = $fieldDataAccessor->getField();
                    $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(FeedbackItemProvider::class, FeedbackItemProvider::E1, [$fieldDataAccessor->getValue('key')]), $field->getArgument('key') ?? $field));
                }
                break;
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function validateResolvedFieldType($objectTypeResolver, $field) : bool
    {
        switch ($field->getName()) {
            case 'metaValue':
            case 'metaValues':
                return \true;
        }
        return parent::validateResolvedFieldType($objectTypeResolver, $field);
    }
}
