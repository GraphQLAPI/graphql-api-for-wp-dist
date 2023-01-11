<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionTokens;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
class CoreGlobalObjectTypeFieldResolver extends \PoP\ComponentModel\FieldResolvers\ObjectType\AbstractGlobalObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\Registries\TypeRegistryInterface|null
     */
    private $typeRegistry;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\Registries\TypeRegistryInterface $typeRegistry
     */
    public final function setTypeRegistry($typeRegistry) : void
    {
        $this->typeRegistry = $typeRegistry;
    }
    protected final function getTypeRegistry() : TypeRegistryInterface
    {
        /** @var TypeRegistryInterface */
        return $this->typeRegistry = $this->typeRegistry ?? $this->instanceManager->getInstance(TypeRegistryInterface::class);
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['_isObjectType', '_implements', '_isInUnionType', '_isTypeOrImplements', '_isTypeOrImplementsAll'];
    }
    /**
     * Do not expose these fields in the Schema
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function skipExposingFieldInSchema($objectTypeResolver, $fieldName) : bool
    {
        return !$this->exposeCoreFunctionalityGlobalFields();
    }
    public function exposeCoreFunctionalityGlobalFields() : bool
    {
        /**
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->exposeCoreFunctionalityGlobalFields();
    }
    /**
     * Only process internally
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($objectTypeResolver, $field) : bool
    {
        if ($this->exposeCoreFunctionalityGlobalFields()) {
            return \true;
        }
        /**
         * Enable when executed within the GraphQL server
         */
        if ($field->getLocation() instanceof RuntimeLocation) {
            return \true;
        }
        /**
         * Disable when invoked from the GraphQL API
         */
        return \false;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case '_isObjectType':
                return $this->getBooleanScalarTypeResolver();
            case '_implements':
                return $this->getBooleanScalarTypeResolver();
            case '_isInUnionType':
                return $this->getBooleanScalarTypeResolver();
            case '_isTypeOrImplements':
                return $this->getBooleanScalarTypeResolver();
            case '_isTypeOrImplementsAll':
                return $this->getBooleanScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case '_isObjectType':
            case '_implements':
            case '_isInUnionType':
            case '_isTypeOrImplements':
            case '_isTypeOrImplementsAll':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case '_isObjectType':
                return $this->__('Indicate if the object is of a given type', 'component-model');
            case '_implements':
                return $this->__('Indicate if the object implements a given interface', 'component-model');
            case '_isInUnionType':
                return $this->__('Indicate if the object is part of a given union type', 'component-model');
            case '_isTypeOrImplements':
                return $this->__('Indicate if the object is of a given type or implements a given interface', 'component-model');
            case '_isTypeOrImplementsAll':
                return $this->__('Indicate if the object is all of the given types or interfaces', 'component-model');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case '_isObjectType':
                return ['type' => $this->getStringScalarTypeResolver()];
            case '_implements':
                return ['interface' => $this->getStringScalarTypeResolver()];
            case '_isInUnionType':
                return ['type' => $this->getStringScalarTypeResolver()];
            case '_isTypeOrImplements':
                return ['typeOrInterface' => $this->getStringScalarTypeResolver()];
            case '_isTypeOrImplementsAll':
                return ['typesOrInterfaces' => $this->getStringScalarTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName) : ?string
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['_isObjectType' => 'type']:
                return $this->__('The type name to compare against', 'component-model');
            case ['_implements' => 'interface']:
                return $this->__('The interface name to compare against', 'component-model');
            case ['_isInUnionType' => 'type']:
                return $this->__('The union type name to compare against', 'component-model');
            case ['_isTypeOrImplements' => 'typeOrInterface']:
                return $this->__('The type or interface name to compare against', 'component-model');
            case ['_isTypeOrImplementsAll' => 'typesOrInterfaces']:
                return $this->__('The types and interface names to compare against', 'component-model');
            default:
                return parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['_isObjectType' => 'type']:
            case ['_implements' => 'interface']:
            case ['_isInUnionType' => 'type']:
            case ['_isTypeOrImplements' => 'typeOrInterface']:
                return SchemaTypeModifiers::MANDATORY;
            case ['_isTypeOrImplementsAll' => 'typesOrInterfaces']:
                return SchemaTypeModifiers::MANDATORY | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case '_isObjectType':
                $typeName = $fieldDataAccessor->getValue('type');
                // If the provided typeName contains the namespace separator, then compare by qualifiedType
                if (\strpos($typeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR) !== \false) {
                    /**
                     * @todo Replace the code below with:
                     *
                     *     return $typeName === $objectTypeResolver->getNamespacedTypeName();
                     *
                     * Currently, because the GraphQL spec doesn't support namespaces,
                     * we are using "_" as the namespace separator, instead of "/".
                     * But this character can also be part of the Type name!
                     * So only temporarily, compare both the namespaced and the
                     * normal type name, until can use "/".
                     *
                     * @see https://github.com/graphql/graphql-spec/issues/163
                     */
                    return $typeName === $objectTypeResolver->getNamespacedTypeName() || $typeName === $objectTypeResolver->getTypeName();
                }
                return $typeName === $objectTypeResolver->getTypeName();
            case '_implements':
                $interface = $fieldDataAccessor->getValue('interface');
                $implementedInterfaceTypeResolvers = $objectTypeResolver->getImplementedInterfaceTypeResolvers();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = \strpos($interface, SchemaDefinitionTokens::NAMESPACE_SEPARATOR) !== \false;
                $implementedInterfaceNames = \array_map(function (InterfaceTypeResolverInterface $interfaceTypeResolver) use($useNamespaced) : string {
                    if ($useNamespaced) {
                        return $interfaceTypeResolver->getNamespacedTypeName();
                    }
                    return $interfaceTypeResolver->getTypeName();
                }, $implementedInterfaceTypeResolvers);
                /**
                 * @todo Remove the block of code below.
                 *
                 * Currently, because the GraphQL spec doesn't support namespaces,
                 * we are using "_" as the namespace separator, instead of "/".
                 * But this character can also be part of the Interface name!
                 * So only temporarily, also add the interface names to the
                 * array to compare, until can use "/".
                 *
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 *
                 * -- Begin code --
                 */
                if ($useNamespaced) {
                    $implementedInterfaceNames = \array_merge($implementedInterfaceNames, \array_map(function (InterfaceTypeResolverInterface $interfaceTypeResolver) : string {
                        return $interfaceTypeResolver->getTypeName();
                    }, $implementedInterfaceTypeResolvers));
                }
                /**
                 * -- End code --
                 */
                return \in_array($interface, $implementedInterfaceNames);
            case '_isInUnionType':
                $unionTypeName = $fieldDataAccessor->getValue('type');
                $unionTypeResolvers = $this->getTypeRegistry()->getUnionTypeResolvers();
                $foundUnionTypeResolver = null;
                /**
                 * If the provided unionTypeName contains the namespace separator, then compare by qualifiedType
                 * @see https://github.com/graphql/graphql-spec/issues/163
                 */
                $isNamespacedUnionTypeName = \strpos($unionTypeName, SchemaDefinitionTokens::NAMESPACE_SEPARATOR) !== \false;
                foreach ($unionTypeResolvers as $unionTypeResolver) {
                    if ($unionTypeName === $unionTypeResolver->getTypeName() || $isNamespacedUnionTypeName && $unionTypeName === $unionTypeResolver->getNamespacedTypeName()) {
                        $foundUnionTypeResolver = $unionTypeResolver;
                        break;
                    }
                }
                if ($foundUnionTypeResolver === null) {
                    return \false;
                }
                /** @var UnionTypeResolverInterface */
                $unionTypeResolver = $foundUnionTypeResolver;
                return $unionTypeResolver->getTargetObjectTypeResolver($object) === $objectTypeResolver;
            case '_isTypeOrImplements':
                $_isObjectType = $objectTypeResolver->resolveValue($object, new LeafField('_isObjectType', null, [new Argument('type', new Literal($fieldDataAccessor->getValue('typeOrInterface'), $fieldDataAccessor->getField()->getLocation()), $fieldDataAccessor->getField()->getLocation())], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($_isObjectType) {
                    return \true;
                }
                $implements = $objectTypeResolver->resolveValue($object, new LeafField('_implements', null, [new Argument('interface', new Literal($fieldDataAccessor->getValue('typeOrInterface'), $fieldDataAccessor->getField()->getLocation()), $fieldDataAccessor->getField()->getLocation())], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($implements) {
                    return \true;
                }
                $isInUnionType = $objectTypeResolver->resolveValue($object, new LeafField('_isInUnionType', null, [new Argument('type', new Literal($fieldDataAccessor->getValue('typeOrInterface'), $fieldDataAccessor->getField()->getLocation()), $fieldDataAccessor->getField()->getLocation())], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return null;
                }
                if ($isInUnionType) {
                    return \true;
                }
                return \false;
            case '_isTypeOrImplementsAll':
                foreach ($fieldDataAccessor->getValue('typesOrInterfaces') as $typeOrInterface) {
                    $isTypeOrInterface = $objectTypeResolver->resolveValue($object, new LeafField('_isTypeOrImplements', null, [new Argument('typeOrInterface', new Literal($typeOrInterface, $fieldDataAccessor->getField()->getLocation()), $fieldDataAccessor->getField()->getLocation())], [], $fieldDataAccessor->getField()->getLocation()), $objectTypeFieldResolutionFeedbackStore);
                    if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                        return null;
                    }
                    if (!$isTypeOrInterface) {
                        return \false;
                    }
                }
                return \true;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
