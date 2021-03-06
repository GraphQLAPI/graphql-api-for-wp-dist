<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use function strpos;
class CoreGlobalFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver
{
    public static function getFieldNamesToResolve() : array
    {
        return ['typeName', 'namespace', 'qualifiedTypeName', 'isType', 'implements'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['typeName' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'namespace' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'qualifiedTypeName' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'isType' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'implements' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'typeName':
            case 'namespace':
            case 'qualifiedTypeName':
            case 'isType':
            case 'implements':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['typeName' => $translationAPI->__('The object\'s type', 'pop-component-model'), 'namespace' => $translationAPI->__('The object\'s namespace', 'pop-component-model'), 'qualifiedTypeName' => $translationAPI->__('The object\'s namespace + type', 'pop-component-model'), 'isType' => $translationAPI->__('Indicate if the object is of a given type', 'pop-component-model'), 'implements' => $translationAPI->__('Indicate if the object implements a given interface', 'pop-component-model')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'isType':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'type', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The type name to compare against', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'implements':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'interface', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The interface name to compare against', 'component-model'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
        }
        return $schemaFieldArgs;
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        switch ($fieldName) {
            case 'typeName':
                return $typeResolver->getTypeName();
            case 'namespace':
                return $typeResolver->getNamespace();
            case 'qualifiedTypeName':
                return $typeResolver->getNamespacedTypeName();
            case 'isType':
                $typeName = $fieldArgs['type'];
                // If the provided typeName contains the namespace separator, then compare by qualifiedType
                if (\strpos($typeName, \PoP\ComponentModel\Schema\SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR) !== \false) {
                    /**
                     * @todo Replace the code below with:
                     *
                     *     return $typeName == $typeResolver->getNamespacedTypeName();
                     *
                     * Currently, because the GraphQL spec doesn't support namespaces,
                     * we are using "_" as the namespace separator, instead of "/".
                     * But this character can also be part of the Type name!
                     * So only temporarily, compare both the namespaced and the
                     * normal type name, until can use "/".
                     *
                     * @see https://github.com/graphql/graphql-spec/issues/163
                     */
                    return $typeName == $typeResolver->getNamespacedTypeName() || $typeName == $typeResolver->getTypeName();
                }
                return $typeName == $typeResolver->getTypeName();
            case 'implements':
                $interface = $fieldArgs['interface'];
                $implementedInterfaceResolverInstances = $typeResolver->getAllImplementedInterfaceResolverInstances();
                // If the provided interface contains the namespace separator, then compare by qualifiedInterface
                $useNamespaced = \strpos($interface, \PoP\ComponentModel\Schema\SchemaDefinition::TOKEN_NAMESPACE_SEPARATOR) !== \false;
                $implementedInterfaceNames = \array_map(function ($interfaceResolver) use($useNamespaced) {
                    if ($useNamespaced) {
                        return $interfaceResolver->getNamespacedInterfaceName();
                    }
                    return $interfaceResolver->getInterfaceName();
                }, $implementedInterfaceResolverInstances);
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
                    $implementedInterfaceNames = \array_merge($implementedInterfaceNames, \array_map(function ($interfaceResolver) {
                        return $interfaceResolver->getInterfaceName();
                    }, $implementedInterfaceResolverInstances));
                }
                /**
                 * -- End code --
                 */
                return \in_array($interface, $implementedInterfaceNames);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
