<?php

declare(strict_types=1);

namespace PoP\Engine\FieldResolvers\Guzzle;

use PoP\GuzzleHelpers\GuzzleHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractGlobalFieldResolver;

class OperatorGlobalFieldResolver extends AbstractGlobalFieldResolver
{
    public static function getFieldNamesToResolve(): array
    {
        return [
            'getJSON',
            'getAsyncJSON',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'getJSON' => SchemaDefinition::TYPE_OBJECT,
            'getAsyncJSON' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_OBJECT),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'getJSON' => $translationAPI->__('Retrieve data from URL and decode it as a JSON object', 'pop-component-model'),
            'getAsyncJSON' => $translationAPI->__('Retrieve data from multiple URL asynchronously, and decode each of them as a JSON object', 'pop-component-model'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'getJSON':
                return array_merge($schemaFieldArgs, [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'url',
                        SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_URL,
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The URL to request', 'pop-component-model'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ]);
            case 'getAsyncJSON':
                return array_merge($schemaFieldArgs, [
                    [
                        SchemaDefinition::ARGNAME_NAME => 'urls',
                        SchemaDefinition::ARGNAME_TYPE => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_URL),
                        SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The URLs to request, with format `key:value`, where the value is the URL, and the key, if provided, is the name where to store the JSON data in the result (if not provided, it is accessed under the corresponding numeric index)', 'pop-component-model'),
                        SchemaDefinition::ARGNAME_MANDATORY => true,
                    ],
                ]);
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
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        switch ($fieldName) {
            case 'getJSON':
                return GuzzleHelpers::requestJSON($fieldArgs['url'], [], 'GET');
            case 'getAsyncJSON':
                return GuzzleHelpers::requestAsyncJSON($fieldArgs['urls'], [], 'GET');
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
