<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\RootMenusFilterInputObjectTypeResolver as UpstreamRootMenusFilterInputObjectTypeResolver;
use PoPWPSchema\Menus\FilterInputs\LocationsFilterInput;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver;

class RootMenusFilterInputObjectTypeResolver extends UpstreamRootMenusFilterInputObjectTypeResolver
{
    /**
     * @var \PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver|null
     */
    private $menuLocationEnumStringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Menus\FilterInputs\LocationsFilterInput|null
     */
    private $locationsFilterInput;

    /**
     * @param \PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver $menuLocationEnumStringScalarTypeResolver
     */
    final public function setMenuLocationEnumStringTypeResolver($menuLocationEnumStringScalarTypeResolver): void
    {
        $this->menuLocationEnumStringScalarTypeResolver = $menuLocationEnumStringScalarTypeResolver;
    }
    final protected function getMenuLocationEnumStringTypeResolver(): MenuLocationEnumStringScalarTypeResolver
    {
        /** @var MenuLocationEnumStringScalarTypeResolver */
        return $this->menuLocationEnumStringScalarTypeResolver = $this->menuLocationEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(MenuLocationEnumStringScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Menus\FilterInputs\LocationsFilterInput $locationsFilterInput
     */
    final public function setLocationsFilterInput($locationsFilterInput): void
    {
        $this->locationsFilterInput = $locationsFilterInput;
    }
    final protected function getLocationsFilterInput(): LocationsFilterInput
    {
        /** @var LocationsFilterInput */
        return $this->locationsFilterInput = $this->locationsFilterInput ?? $this->instanceManager->getInstance(LocationsFilterInput::class);
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(parent::getInputFieldNameTypeResolvers(), [
            'locations' => $this->getMenuLocationEnumStringTypeResolver(),
        ]);
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'locations':
                return $this->__('Filter menus based on locations', 'menus');
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
            case 'locations':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName): ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'locations':
                return $this->getLocationsFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
