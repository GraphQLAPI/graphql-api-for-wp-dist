<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Exception\ImpossibleToHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPWPSchema\Meta\Constants\MetaQueryCompareByOperators;
use PoPWPSchema\Meta\Constants\MetaQueryValueTypes;
use PoPWPSchema\Meta\FeedbackItemProviders\FeedbackItemProvider;
use PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryValueTypeEnumTypeResolver;
use PoPWPSchema\SchemaCommons\Constants\Relation;
use PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver;
use stdClass;

abstract class AbstractMetaQueryInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryValueTypeEnumTypeResolver|null
     */
    private $metaQueryValueTypesEnumTypeResolver;
    /**
     * @var \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByOneofInputObjectTypeResolver|null
     */
    private $metaQueryCompareByOneofInputObjectTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver|null
     */
    private $relationEnumTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface|null
     */
    private $allowOrDenySettingsService;

    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\EnumType\MetaQueryValueTypeEnumTypeResolver $metaQueryValueTypesEnumTypeResolver
     */
    final public function setMetaQueryValueTypesEnumTypeResolver($metaQueryValueTypesEnumTypeResolver): void
    {
        $this->metaQueryValueTypesEnumTypeResolver = $metaQueryValueTypesEnumTypeResolver;
    }
    final protected function getMetaQueryValueTypesEnumTypeResolver(): MetaQueryValueTypeEnumTypeResolver
    {
        /** @var MetaQueryValueTypeEnumTypeResolver */
        return $this->metaQueryValueTypesEnumTypeResolver = $this->metaQueryValueTypesEnumTypeResolver ?? $this->instanceManager->getInstance(MetaQueryValueTypeEnumTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Meta\TypeResolvers\InputObjectType\MetaQueryCompareByOneofInputObjectTypeResolver $metaQueryCompareByOneofInputObjectTypeResolver
     */
    final public function setMetaQueryCompareByOneofInputObjectTypeResolver($metaQueryCompareByOneofInputObjectTypeResolver): void
    {
        $this->metaQueryCompareByOneofInputObjectTypeResolver = $metaQueryCompareByOneofInputObjectTypeResolver;
    }
    final protected function getMetaQueryCompareByOneofInputObjectTypeResolver(): MetaQueryCompareByOneofInputObjectTypeResolver
    {
        /** @var MetaQueryCompareByOneofInputObjectTypeResolver */
        return $this->metaQueryCompareByOneofInputObjectTypeResolver = $this->metaQueryCompareByOneofInputObjectTypeResolver ?? $this->instanceManager->getInstance(MetaQueryCompareByOneofInputObjectTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    final public function setStringScalarTypeResolver($stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\SchemaCommons\TypeResolvers\EnumType\RelationEnumTypeResolver $relationEnumTypeResolver
     */
    final public function setRelationEnumTypeResolver($relationEnumTypeResolver): void
    {
        $this->relationEnumTypeResolver = $relationEnumTypeResolver;
    }
    final protected function getRelationEnumTypeResolver(): RelationEnumTypeResolver
    {
        /** @var RelationEnumTypeResolver */
        return $this->relationEnumTypeResolver = $this->relationEnumTypeResolver ?? $this->instanceManager->getInstance(RelationEnumTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface $allowOrDenySettingsService
     */
    final public function setAllowOrDenySettingsService($allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }
    final protected function getAllowOrDenySettingsService(): AllowOrDenySettingsServiceInterface
    {
        /** @var AllowOrDenySettingsServiceInterface */
        return $this->allowOrDenySettingsService = $this->allowOrDenySettingsService ?? $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Filter by meta key and value. The key must be allowed access in the Settings. See: https://developer.wordpress.org/reference/classes/wp_meta_query/', 'meta');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'key' => $this->getStringScalarTypeResolver(),
            'type' => $this->getMetaQueryValueTypesEnumTypeResolver(),
            'compareBy' => $this->getMetaQueryCompareByOneofInputObjectTypeResolver(),
            'relation' => $this->getRelationEnumTypeResolver(),
        ];
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'key':
                return $this->__('Custom field key', 'meta');
            case 'type':
                return $this->__('Custom field type', 'meta');
            case 'compareBy':
                return $this->__('Value and operator to compare against', 'meta');
            case 'relation':
                return $this->__('OR or AND, how the sub-arrays should be compared. Default: AND. Only the value from the first sub-array will be used', 'meta');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }

    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'type':
                return MetaQueryValueTypes::CHAR;
            case 'relation':
                return Relation::AND;
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName): int
    {
        switch ($inputFieldName) {
            case 'key':
            case 'type':
            case 'compareBy':
            case 'relation':
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }

    /**
     * Custom validations to execute on the input field.
     * @param mixed $coercedInputFieldValue
     * @param \PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface $inputFieldTypeResolver
     * @param string $inputFieldName
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function validateCoercedInputFieldValue($inputFieldTypeResolver, $inputFieldName, $coercedInputFieldValue, $astNode, $objectTypeFieldResolutionFeedbackStore): void
    {
        switch ($inputFieldName) {
            case 'key':
                if (
                    !$this->getAllowOrDenySettingsService()->isEntryAllowed($coercedInputFieldValue, $this->getAllowOrDenyEntries(), $this->getAllowOrDenyBehavior())
                ) {
                    $objectTypeFieldResolutionFeedbackStore->addError(new ObjectTypeFieldResolutionFeedback(new FeedbackItemResolution(
                        FeedbackItemProvider::class,
                        FeedbackItemProvider::E1,
                        [
                            $coercedInputFieldValue,
                        ]
                    ), $astNode));
                    return;
                }
                break;
        }
        parent::validateCoercedInputFieldValue($inputFieldTypeResolver, $inputFieldName, $coercedInputFieldValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * @return string[]
     */
    abstract protected function getAllowOrDenyEntries(): array;
    abstract protected function getAllowOrDenyBehavior(): string;

    /**
     * Integrate parameters into the "meta_query" WP_Query arg
     *
     * @see https://developer.wordpress.org/reference/classes/wp_meta_query/
     *
     * @param array<string,mixed> $query
     * @param stdClass|stdClass[]|array<stdClass[]> $inputValue
     */
    public function integrateInputValueToFilteringQueryArgs(&$query, $inputValue): void
    {
        /** @var stdClass[] $inputValue */
        $metaQuery = [];

        /**
         * Get the "relation" from the first element only
         */
        $firstInputValueElem = $inputValue[0];
        if (property_exists($firstInputValueElem, 'relation')) {
            $metaQuery['relation'] = $firstInputValueElem->relation;
        }

        foreach ($inputValue as $inputValueElem) {
            $metaQueryElem = [
                'key' => $inputValueElem->key,
                'type' => $inputValueElem->type,
            ];
            $value = $operator = null;
            if (isset($inputValueElem->compareBy->key)) {
                $operator = $inputValueElem->compareBy->key->operator;
            } elseif (isset($inputValueElem->compareBy->numericValue)) {
                $value = $inputValueElem->compareBy->numericValue->value;
                $operator = $inputValueElem->compareBy->numericValue->operator;
            } elseif (isset($inputValueElem->compareBy->stringValue)) {
                $value = $inputValueElem->compareBy->stringValue->value;
                $operator = $inputValueElem->compareBy->stringValue->operator;
            } elseif (isset($inputValueElem->compareBy->arrayValue)) {
                $value = $inputValueElem->compareBy->arrayValue->value;
                $operator = $inputValueElem->compareBy->arrayValue->operator;
            } else {
                // It will never reach here
                continue;
            }
            if ($value !== null) {
                $metaQueryElem['value'] = $value;
            }
            if ($operator !== null) {
                $metaQueryElem['compare'] = $this->getOperatorFromInputValue($operator);
            }
            $metaQuery[] = $metaQueryElem;
        }
        if ($metaQuery !== []) {
            $query['meta_query'] = $metaQuery;
        }
    }

    /**
     * @param string $operator
     */
    protected function getOperatorFromInputValue($operator): string
    {
        switch ($operator) {
            case MetaQueryCompareByOperators::EQUALS:
                return '=';
            case MetaQueryCompareByOperators::NOT_EQUALS:
                return '!=';
            case MetaQueryCompareByOperators::GREATER_THAN:
                return '>';
            case MetaQueryCompareByOperators::GREATER_THAN_OR_EQUAL:
                return '>=';
            case MetaQueryCompareByOperators::LESS_THAN:
                return '<';
            case MetaQueryCompareByOperators::LESS_THAN_OR_EQUAL:
                return '<=';
            case MetaQueryCompareByOperators::LIKE:
                return 'LIKE';
            case MetaQueryCompareByOperators::NOT_LIKE:
                return 'NOT LIKE';
            case MetaQueryCompareByOperators::IN:
                return 'IN';
            case MetaQueryCompareByOperators::NOT_IN:
                return 'NOT IN';
            case MetaQueryCompareByOperators::BETWEEN:
                return 'BETWEEN';
            case MetaQueryCompareByOperators::NOT_BETWEEN:
                return 'NOT BETWEEN';
            case MetaQueryCompareByOperators::EXISTS:
                return 'EXISTS';
            case MetaQueryCompareByOperators::NOT_EXISTS:
                return 'NOT EXISTS';
            case MetaQueryCompareByOperators::REGEXP:
                return 'REGEXP';
            case MetaQueryCompareByOperators::NOT_REGEXP:
                return 'NOT REGEXP';
            case MetaQueryCompareByOperators::RLIKE:
                return 'RLIKE';
            default:
                throw new ImpossibleToHappenException(sprintf('Unknown operator \'%s\'', $operator));
        }
    }
}
