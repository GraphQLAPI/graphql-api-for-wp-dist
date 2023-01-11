<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\InputObjectType\MenuByInputObjectTypeResolver as UpstreamMenuByInputObjectTypeResolver;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver;

class MenuByInputObjectTypeResolver extends UpstreamMenuByInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver|null
     */
    private $menuLocationEnumStringScalarTypeResolver;

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
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'slug' => $this->getStringScalarTypeResolver(),
                'location' => $this->getMenuLocationEnumStringTypeResolver(),
            ]
        );
    }

    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName): ?string
    {
        switch ($inputFieldName) {
            case 'slug':
                return $this->__('Query by slug', 'menus');
            case 'location':
                return $this->__('Query by location', 'menus');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}
