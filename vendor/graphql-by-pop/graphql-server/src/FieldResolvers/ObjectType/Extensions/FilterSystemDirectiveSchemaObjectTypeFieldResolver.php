<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\SchemaObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\ObjectModels\Schema;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver;
use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\SchemaObjectTypeResolver;
use PoPAPI\API\Schema\SchemaDefinition;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Registries\DirectiveRegistryInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
class FilterSystemDirectiveSchemaObjectTypeFieldResolver extends SchemaObjectTypeFieldResolver
{
    /**
     * @var \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver|null
     */
    private $directiveKindEnumTypeResolver;
    /**
     * @var \PoP\ComponentModel\Registries\DirectiveRegistryInterface|null
     */
    private $directiveRegistry;
    /**
     * @param \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\DirectiveKindEnumTypeResolver $directiveKindEnumTypeResolver
     */
    public final function setDirectiveKindEnumTypeResolver($directiveKindEnumTypeResolver) : void
    {
        $this->directiveKindEnumTypeResolver = $directiveKindEnumTypeResolver;
    }
    protected final function getDirectiveKindEnumTypeResolver() : DirectiveKindEnumTypeResolver
    {
        /** @var DirectiveKindEnumTypeResolver */
        return $this->directiveKindEnumTypeResolver = $this->directiveKindEnumTypeResolver ?? $this->instanceManager->getInstance(DirectiveKindEnumTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\Registries\DirectiveRegistryInterface $directiveRegistry
     */
    public final function setDirectiveRegistry($directiveRegistry) : void
    {
        $this->directiveRegistry = $directiveRegistry;
    }
    protected final function getDirectiveRegistry() : DirectiveRegistryInterface
    {
        /** @var DirectiveRegistryInterface */
        return $this->directiveRegistry = $this->directiveRegistry ?? $this->instanceManager->getInstance(DirectiveRegistryInterface::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [SchemaObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['directives'];
    }
    public function getPriorityToAttachToClasses() : int
    {
        // Higher priority => Process first
        return 100;
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'directives':
                return ['ofKinds' => $this->getDirectiveKindEnumTypeResolver()];
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
            case ['directives' => 'ofKinds']:
                return $this->__('Include only directives of provided types', 'graphql-api');
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
            case ['directives' => 'ofKinds']:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
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
        /** @var Schema */
        $schema = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofKinds = $fieldDataAccessor->getValue('ofKinds')) {
                    $ofTypeFieldDirectiveResolvers = \array_filter($this->getDirectiveRegistry()->getFieldDirectiveResolvers(), function (FieldDirectiveResolverInterface $directiveResolver) use($ofKinds) {
                        return \in_array($directiveResolver->getDirectiveKind(), $ofKinds);
                    });
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = \array_map(function (FieldDirectiveResolverInterface $directiveResolver) : string {
                        // To retrieve the ID, use the same method to calculate the ID
                        // used when creating a new Directive instance
                        // (which we can't do here, since it has side-effects)
                        $directiveSchemaDefinitionPath = [SchemaDefinition::GLOBAL_DIRECTIVES, $directiveResolver->getDirectiveName()];
                        return SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID($directiveSchemaDefinitionPath);
                    }, $ofTypeFieldDirectiveResolvers);
                    return \array_values(\array_intersect($directiveIDs, $ofTypeDirectiveIDs));
                }
                return $directiveIDs;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
