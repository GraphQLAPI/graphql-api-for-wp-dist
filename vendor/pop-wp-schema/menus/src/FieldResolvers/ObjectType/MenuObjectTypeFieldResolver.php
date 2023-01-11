<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPWPSchema\Menus\TypeResolvers\ScalarType\MenuLocationEnumStringScalarTypeResolver;
use WP_Term;

class MenuObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    final public function setIntScalarTypeResolver($intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
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
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MenuObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'name',
            'slug',
            'count',
            'locations',
        ];
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'name':
            case 'slug':
                return $this->getStringScalarTypeResolver();
            case 'locations':
                return $this->getMenuLocationEnumStringTypeResolver();
            case 'count':
                return $this->getIntScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName): int
    {
        switch ($fieldName) {
            case 'count':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'locations':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName): ?string
    {
        switch ($fieldName) {
            case 'name':
                return $this->__('Menu\'s name', 'pop-menus');
            case 'slug':
                return $this->__('Menu\'s slug', 'pop-menus');
            case 'count':
                return $this->__('Number of items contained in the menu', 'pop-menus');
            case 'locations':
                return $this->__('To which locations has the menu been assigned to', 'pop-menus');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
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
        /** @var WP_Term */
        $menu = $object;
        $menuID = $objectTypeResolver->getID($menu);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'name':
                return $menu->name;
            case 'slug':
                return $menu->slug;
            case 'count':
                return $menu->count;
            case 'locations':
                $locationMenuIDs = \get_nav_menu_locations();
                return array_keys(
                    array_filter(
                        $locationMenuIDs,
                        function ($locationMenuID) use ($menuID) {
                            return $locationMenuID === $menuID;
                        }
                    )
                );
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
