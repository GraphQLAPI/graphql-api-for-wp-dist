<?php

declare (strict_types=1);
namespace PoP\Engine\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Directives\FieldDirectiveBehaviors;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\Engine\FeedbackItemProviders\ErrorFeedbackItemProvider;
use PoP\Engine\ObjectModels\SuperRoot;
use PoP\Engine\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader;
use PoP\Engine\StaticHelpers\SuperRootHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
class SuperRootObjectTypeResolver extends AbstractObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    /**
     * @var \PoP\Engine\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader|null
     */
    private $superRootTypeDataLoader;
    /**
     * @var \GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface|null
     */
    private $mandatoryOperationDirectiveResolverRegistry;
    /**
     * @param \PoP\Engine\RelationalTypeDataLoaders\ObjectType\SuperRootTypeDataLoader $superRootTypeDataLoader
     */
    public final function setSuperRootTypeDataLoader($superRootTypeDataLoader) : void
    {
        $this->superRootTypeDataLoader = $superRootTypeDataLoader;
    }
    protected final function getSuperRootTypeDataLoader() : SuperRootTypeDataLoader
    {
        /** @var SuperRootTypeDataLoader */
        return $this->superRootTypeDataLoader = $this->superRootTypeDataLoader ?? $this->instanceManager->getInstance(SuperRootTypeDataLoader::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface $mandatoryOperationDirectiveResolverRegistry
     */
    public final function setMandatoryOperationDirectiveResolverRegistry($mandatoryOperationDirectiveResolverRegistry) : void
    {
        $this->mandatoryOperationDirectiveResolverRegistry = $mandatoryOperationDirectiveResolverRegistry;
    }
    protected final function getMandatoryOperationDirectiveResolverRegistry() : MandatoryOperationDirectiveResolverRegistryInterface
    {
        /** @var MandatoryOperationDirectiveResolverRegistryInterface */
        return $this->mandatoryOperationDirectiveResolverRegistry = $this->mandatoryOperationDirectiveResolverRegistry ?? $this->instanceManager->getInstance(MandatoryOperationDirectiveResolverRegistryInterface::class);
    }
    public function getTypeName() : string
    {
        return 'SuperRoot';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('(Internal) Super Root type, starting from which the query is executed', 'engine');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var SuperRoot */
        $superRoot = $object;
        return $superRoot->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getSuperRootTypeDataLoader();
    }
    /**
     * Provide the mandatory directives for Operations.
     *
     * @return FieldDirectiveResolverInterface[]
     */
    protected function getMandatoryFieldOrOperationDirectiveResolvers() : array
    {
        return $this->getMandatoryOperationDirectiveResolverRegistry()->getMandatoryOperationDirectiveResolvers();
    }
    /**
     * Satisfy for Operation Directives
     */
    protected function getSupportedDirectiveLocationsByBehavior() : array
    {
        return [FieldDirectiveBehaviors::OPERATION, FieldDirectiveBehaviors::FIELD, FieldDirectiveBehaviors::FIELD_AND_OPERATION];
    }
    /**
     * Provide a different error message for the SuperRoot field,
     * as it represents an Operation and not a Field
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldNotResolvedByObjectTypeFeedbackItemResolution($field) : FeedbackItemResolution
    {
        $operation = SuperRootHelper::getOperationFromSuperRootFieldName($field->getName());
        if ($operation !== null) {
            return new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E1, [$operation]);
        }
        return new FeedbackItemResolution(ErrorFeedbackItemProvider::class, ErrorFeedbackItemProvider::E1A);
    }
}
