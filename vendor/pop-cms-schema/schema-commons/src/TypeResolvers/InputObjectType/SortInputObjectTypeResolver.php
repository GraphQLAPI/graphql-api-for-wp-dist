<?php

declare (strict_types=1);
namespace PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\OrderByFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\OrderFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver;
use PoPSchema\SchemaCommons\Constants\Order;
class SortInputObjectTypeResolver extends AbstractQueryableInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver|null
     */
    private $orderEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\OrderByFilterInput|null
     */
    private $excludeIDsFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\OrderFilterInput|null
     */
    private $includeFilterInput;
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
     * @param \PoPCMSSchema\SchemaCommons\TypeResolvers\EnumType\OrderEnumTypeResolver $orderEnumTypeResolver
     */
    public final function setOrderEnumTypeResolver($orderEnumTypeResolver) : void
    {
        $this->orderEnumTypeResolver = $orderEnumTypeResolver;
    }
    protected final function getOrderEnumTypeResolver() : OrderEnumTypeResolver
    {
        /** @var OrderEnumTypeResolver */
        return $this->orderEnumTypeResolver = $this->orderEnumTypeResolver ?? $this->instanceManager->getInstance(OrderEnumTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\OrderByFilterInput $excludeIDsFilterInput
     */
    public final function setOrderByFilterInput($excludeIDsFilterInput) : void
    {
        $this->excludeIDsFilterInput = $excludeIDsFilterInput;
    }
    protected final function getOrderByFilterInput() : OrderByFilterInput
    {
        /** @var OrderByFilterInput */
        return $this->excludeIDsFilterInput = $this->excludeIDsFilterInput ?? $this->instanceManager->getInstance(OrderByFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\OrderFilterInput $includeFilterInput
     */
    public final function setOrderFilterInput($includeFilterInput) : void
    {
        $this->includeFilterInput = $includeFilterInput;
    }
    protected final function getOrderFilterInput() : OrderFilterInput
    {
        /** @var OrderFilterInput */
        return $this->includeFilterInput = $this->includeFilterInput ?? $this->instanceManager->getInstance(OrderFilterInput::class);
    }
    public function getTypeName() : string
    {
        return 'SortInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to sort custom posts', 'customposts');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['order' => $this->getOrderEnumTypeResolver(), 'by' => $this->getStringScalarTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'order':
                return $this->__('Sorting direction', 'schema-commons');
            case 'by':
                return $this->__('Property to order by', 'schema-commons');
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
            case 'order':
                return Order::DESC;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'order':
                return $this->getOrderFilterInput();
            case 'by':
                return $this->getOrderByFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
