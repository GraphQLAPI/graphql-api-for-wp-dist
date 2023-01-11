<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

use GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\MutationRoot;
use GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\RelationalTypeDataLoaderInterface;
use PoP\ComponentModel\TypeResolvers\CanonicalTypeNameTypeResolverTrait;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;
class MutationRootObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractUseRootAsSourceForSchemaObjectTypeResolver
{
    use CanonicalTypeNameTypeResolverTrait;
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;
    /**
     * @var \GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface|null
     */
    private $typeResolverHelper;
    /**
     * @var \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader|null
     */
    private $mutationRootTypeDataLoader;
    /**
     * @param \GraphQLByPoP\GraphQLServer\Helpers\TypeResolverHelperInterface $typeResolverHelper
     */
    public final function setTypeResolverHelper($typeResolverHelper) : void
    {
        $this->typeResolverHelper = $typeResolverHelper;
    }
    protected final function getTypeResolverHelper() : TypeResolverHelperInterface
    {
        /** @var TypeResolverHelperInterface */
        return $this->typeResolverHelper = $this->typeResolverHelper ?? $this->instanceManager->getInstance(TypeResolverHelperInterface::class);
    }
    /**
     * @param \GraphQLByPoP\GraphQLServer\RelationalTypeDataLoaders\ObjectType\MutationRootTypeDataLoader $mutationRootTypeDataLoader
     */
    public final function setMutationRootTypeDataLoader($mutationRootTypeDataLoader) : void
    {
        $this->mutationRootTypeDataLoader = $mutationRootTypeDataLoader;
    }
    protected final function getMutationRootTypeDataLoader() : MutationRootTypeDataLoader
    {
        /** @var MutationRootTypeDataLoader */
        return $this->mutationRootTypeDataLoader = $this->mutationRootTypeDataLoader ?? $this->instanceManager->getInstance(MutationRootTypeDataLoader::class);
    }
    public function getTypeName() : string
    {
        return 'MutationRoot';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Mutation type, starting from which mutations are executed', 'graphql-server');
    }
    /**
     * @return string|int|null
     * @param object $object
     */
    public function getID($object)
    {
        /** @var MutationRoot */
        $mutationRoot = $object;
        return $mutationRoot->getID();
    }
    public function getRelationalTypeDataLoader() : RelationalTypeDataLoaderInterface
    {
        return $this->getMutationRootTypeDataLoader();
    }
    /**
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface $objectTypeFieldResolver
     * @param string $fieldName
     */
    public function isFieldNameConditionSatisfiedForSchema($objectTypeFieldResolver, $fieldName) : bool
    {
        $objectTypeResolverMandatoryFields = $this->getTypeResolverHelper()->getObjectTypeResolverMandatoryFields();
        return \in_array($fieldName, $objectTypeResolverMandatoryFields) || $objectTypeFieldResolver->getFieldMutationResolver($this, $fieldName) !== null;
    }
    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    protected final function consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers) : array
    {
        return $this->removeIdentifiableObjectInterfaceTypeFieldResolver(parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers));
    }
}
