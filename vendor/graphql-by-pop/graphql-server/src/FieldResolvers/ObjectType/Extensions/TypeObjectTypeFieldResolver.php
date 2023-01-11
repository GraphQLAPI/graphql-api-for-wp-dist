<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\Extensions;

use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\TypeObjectTypeFieldResolver as UpstreamTypeObjectTypeFieldResolver;
use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\ObjectModels\HasFieldsTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\NamedTypeInterface;
use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
class TypeObjectTypeFieldResolver extends UpstreamTypeObjectTypeFieldResolver
{
    public function getPriorityToAttachToClasses() : int
    {
        // Higher priority => Process first
        return 100;
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_merge(['name'], $moduleConfiguration->exposeGlobalFieldsInGraphQLSchema() ? ['fields'] : []);
    }
    /**
     * Only use this fieldResolver when parameter `namespaced` is provided. Otherwise, use the default implementation
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function resolveCanProcessField($objectTypeResolver, $field) : bool
    {
        switch ($field->getName()) {
            case 'name':
                return $field->hasArgument('namespaced');
            case 'fields':
                return $field->hasArgument('includeGlobal');
            default:
                return parent::resolveCanProcessField($objectTypeResolver, $field);
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
            case 'name':
                return ['namespaced' => $this->getBooleanScalarTypeResolver()];
            case 'fields':
                $item0Unpacked = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
                return \array_merge($item0Unpacked, ['includeGlobal' => $this->getBooleanScalarTypeResolver()]);
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
            case ['name' => 'namespaced']:
                return $this->__('Namespace type name?', 'graphql-server');
            case ['fields' => 'includeGlobal']:
                return $this->__('Include global fields?', 'graphql-server');
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
            case ['name' => 'namespaced']:
                return SchemaTypeModifiers::MANDATORY;
            case ['fields' => 'includeGlobal']:
                return SchemaTypeModifiers::MANDATORY;
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
        /** @var NamedTypeInterface */
        $type = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                if ($fieldDataAccessor->getValue('namespaced')) {
                    return $type->getNamespacedName();
                }
                return $type->getElementName();
            case 'fields':
                // From GraphQL spec (https://graphql.github.io/graphql-spec/draft/#sel-FAJbLAC1BJC3BAn6e):
                // "should be non-null for OBJECT and INTERFACE only, must be null for the others"
                if ($type instanceof HasFieldsTypeInterface) {
                    /**
                     * Only include the global fields for Objects!
                     * (i.e. do not for Interfaces)
                     */
                    $includeGlobal = $type->getKind() === TypeKinds::OBJECT ? $fieldDataAccessor->getValue('includeGlobal') ?? \true : \false;
                    return $type->getFieldIDs($fieldDataAccessor->getValue('includeDeprecated') ?? \false, $includeGlobal);
                }
                return null;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
