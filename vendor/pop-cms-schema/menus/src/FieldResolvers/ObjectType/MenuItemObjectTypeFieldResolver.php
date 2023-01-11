<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Menus\ObjectModels\MenuItem;
use PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
class MenuItemObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface|null
     */
    private $menuItemRuntimeRegistry;
    /**
     * @var \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface|null
     */
    private $cmsHelperService;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver|null
     */
    private $urlScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver|null
     */
    private $menuItemObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Menus\RuntimeRegistries\MenuItemRuntimeRegistryInterface $menuItemRuntimeRegistry
     */
    public final function setMenuItemRuntimeRegistry($menuItemRuntimeRegistry) : void
    {
        $this->menuItemRuntimeRegistry = $menuItemRuntimeRegistry;
    }
    protected final function getMenuItemRuntimeRegistry() : MenuItemRuntimeRegistryInterface
    {
        /** @var MenuItemRuntimeRegistryInterface */
        return $this->menuItemRuntimeRegistry = $this->menuItemRuntimeRegistry ?? $this->instanceManager->getInstance(MenuItemRuntimeRegistryInterface::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\CMS\CMSHelperServiceInterface $cmsHelperService
     */
    public final function setCMSHelperService($cmsHelperService) : void
    {
        $this->cmsHelperService = $cmsHelperService;
    }
    protected final function getCMSHelperService() : CMSHelperServiceInterface
    {
        /** @var CMSHelperServiceInterface */
        return $this->cmsHelperService = $this->cmsHelperService ?? $this->instanceManager->getInstance(CMSHelperServiceInterface::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver $urlScalarTypeResolver
     */
    public final function setURLScalarTypeResolver($urlScalarTypeResolver) : void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected final function getURLScalarTypeResolver() : URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver = $this->urlScalarTypeResolver ?? $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
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
     * @param \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuItemObjectTypeResolver $menuItemObjectTypeResolver
     */
    public final function setMenuItemObjectTypeResolver($menuItemObjectTypeResolver) : void
    {
        $this->menuItemObjectTypeResolver = $menuItemObjectTypeResolver;
    }
    protected final function getMenuItemObjectTypeResolver() : MenuItemObjectTypeResolver
    {
        /** @var MenuItemObjectTypeResolver */
        return $this->menuItemObjectTypeResolver = $this->menuItemObjectTypeResolver ?? $this->instanceManager->getInstance(MenuItemObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [MenuItemObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return [
            // This field is special in that it is retrieved from the registry
            'children',
            'localURLPath',
            // All other fields are properties in the object
            'label',
            'title',
            'url',
            'classes',
            'target',
            'description',
            'objectID',
            'parentID',
            'linkRelationship',
        ];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'children':
                return $this->getMenuItemObjectTypeResolver();
            case 'localURLPath':
                return $this->getStringScalarTypeResolver();
            case 'label':
                return $this->getStringScalarTypeResolver();
            case 'title':
                return $this->getStringScalarTypeResolver();
            case 'url':
                return $this->getURLScalarTypeResolver();
            case 'classes':
                return $this->getStringScalarTypeResolver();
            case 'target':
                return $this->getStringScalarTypeResolver();
            case 'description':
                return $this->getStringScalarTypeResolver();
            case 'objectID':
                return $this->getIDScalarTypeResolver();
            case 'parentID':
                return $this->getIDScalarTypeResolver();
            case 'linkRelationship':
                return $this->getStringScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'children':
            case 'classes':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'children':
                return $this->__('Menu item children items', 'menus');
            case 'label':
                return $this->__('Menu item label', 'menus');
            case 'title':
                return $this->__('Menu item title', 'menus');
            case 'localURLPath':
                return $this->__('Path of a local URL, or null if external URL', 'menus');
            case 'url':
                return $this->__('Menu item URL', 'menus');
            case 'classes':
                return $this->__('Menu item classes', 'menus');
            case 'target':
                return $this->__('Menu item target', 'menus');
            case 'description':
                return $this->__('Menu item additional attributes', 'menus');
            case 'objectID':
                return $this->__('ID of the object linked to by the menu item ', 'menus');
            case 'parentID':
                return $this->__('Menu item\'s parent ID', 'menus');
            case 'linkRelationship':
                return $this->__('Link relationship (XFN)', 'menus');
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
        /** @var MenuItem */
        $menuItem = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'children':
                return \array_keys($this->getMenuItemRuntimeRegistry()->getMenuItemChildren($menuItem));
            case 'localURLPath':
                $url = $menuItem->url;
                return $this->getCMSHelperService()->getLocalURLPath($url);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
