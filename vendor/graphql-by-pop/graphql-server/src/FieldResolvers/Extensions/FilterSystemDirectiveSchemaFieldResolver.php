<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\FieldResolvers\Extensions;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use GraphQLByPoP\GraphQLServer\Enums\DirectiveTypeEnum;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver;
use GraphQLByPoP\GraphQLServer\FieldResolvers\SchemaFieldResolver;
use PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
class FilterSystemDirectiveSchemaFieldResolver extends \GraphQLByPoP\GraphQLServer\FieldResolvers\SchemaFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\GraphQLByPoP\GraphQLServer\TypeResolvers\SchemaTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['directives'];
    }
    // /**
    //  * Only use this fieldResolver when parameter `ofTypes` is provided.
    //  * Otherwise, use the default implementation
    //  *
    //  * @param TypeResolverInterface $typeResolver
    //  * @param string $fieldName
    //  * @param array<string, mixed> $fieldArgs
    //  * @return boolean
    //  */
    // public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    // {
    //     return $fieldName == 'directives' && isset($fieldArgs['ofTypes']);
    // }
    // public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    // {
    //     $translationAPI = TranslationAPIFacade::getInstance();
    //     $descriptions = [
    //         'directives' => $translationAPI->__('All directives registered in the data graph, allowing to remove the system directives', 'graphql-api'),
    //     ];
    //     return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    // }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'directives':
                /**
                 * @var DirectiveTypeEnum
                 */
                $directiveTypeEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\DirectiveTypeEnum::class);
                return \array_merge($schemaFieldArgs, [[
                    \PoP\API\Schema\SchemaDefinition::ARGNAME_NAME => 'ofTypes',
                    \PoP\API\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\TypeCastingHelpers::makeArray(\PoP\API\Schema\SchemaDefinition::TYPE_ENUM),
                    \PoP\API\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Include only directives of provided types', 'graphql-api'),
                    // SchemaDefinition::ARGNAME_MANDATORY => true,
                    // SchemaDefinition::ARGNAME_DEFAULT_VALUE => [
                    //     DirectiveTypes::QUERY,
                    // ],
                    \PoP\API\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $directiveTypeEnum->getName(),
                    \PoP\API\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($directiveTypeEnum->getValues()),
                ]]);
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
        $schema = $resultItem;
        switch ($fieldName) {
            case 'directives':
                $directiveIDs = $schema->getDirectiveIDs();
                if ($ofTypes = $fieldArgs['ofTypes'] ?? null) {
                    $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
                    /**
                     * @var DirectiveTypeEnum
                     */
                    $directiveTypeEnum = $instanceManager->getInstance(\GraphQLByPoP\GraphQLServer\Enums\DirectiveTypeEnum::class);
                    // Convert the enum from uppercase (as exposed in the API) to lowercase (as is its real value)
                    $ofTypes = \array_map([$directiveTypeEnum, 'getCoreValue'], $ofTypes);
                    $directiveRegistry = \PoP\ComponentModel\Facades\Registries\DirectiveRegistryFacade::getInstance();
                    $ofTypeDirectiveResolverClasses = \array_filter($directiveRegistry->getDirectiveResolverClasses(), function ($directiveResolverClass) use($instanceManager, $ofTypes) {
                        /**
                         * @var DirectiveResolverInterface
                         */
                        $directiveResolver = $instanceManager->getInstance($directiveResolverClass);
                        return \in_array($directiveResolver->getDirectiveType(), $ofTypes);
                    });
                    // Calculate the directive IDs
                    $ofTypeDirectiveIDs = \array_map(function ($directiveResolverClass) {
                        // To retrieve the ID, use the same method to calculate the ID
                        // used when creating a new Directive instance
                        // (which we can't do here, since it has side-effects)
                        $directiveSchemaDefinitionPath = [\PoP\API\Schema\SchemaDefinition::ARGNAME_GLOBAL_DIRECTIVES, $directiveResolverClass::getDirectiveName()];
                        return \GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers::getID($directiveSchemaDefinitionPath);
                    }, $ofTypeDirectiveResolverClasses);
                    return \array_intersect($directiveIDs, $ofTypeDirectiveIDs);
                }
                return $directiveIDs;
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
