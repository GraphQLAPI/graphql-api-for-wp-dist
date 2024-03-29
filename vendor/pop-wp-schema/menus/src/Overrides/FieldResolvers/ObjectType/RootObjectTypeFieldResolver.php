<?php

declare(strict_types=1);

namespace PoPWPSchema\Menus\Overrides\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoPCMSSchema\Menus\FieldResolvers\ObjectType\RootObjectTypeFieldResolver as UpstreamRootObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RootObjectTypeFieldResolver extends UpstreamRootObjectTypeFieldResolver
{
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'menu':
                $menuParam = null;
                $by = $fieldDataAccessor->getValue('by');
                if (isset($by->slug)) {
                    $menuParam = $by->slug;
                } elseif (isset($by->location)) {
                    $locations = \get_nav_menu_locations();
                    $menuParam = $locations[$by->location] ?? null;
                    if ($menuParam === null) {
                        return null;
                    }
                }
                if ($menuParam === null) {
                    return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
                }
                $menu = wp_get_nav_menu_object($menuParam);
                if ($menu === false) {
                    return null;
                }
                return $menu->term_id;
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
