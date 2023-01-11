<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPosts\Module;
use PoPCMSSchema\CustomPosts\ModuleConfiguration;
use PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractGenericCustomPostObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements \PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver|null
     */
    private $genericCustomPostObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;
    /**
     * @var \PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface|null
     */
    private $customPostObjectTypeResolverPickerRegistry;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver
     */
    public final function setGenericCustomPostObjectTypeResolver($genericCustomPostObjectTypeResolver) : void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    protected final function getGenericCustomPostObjectTypeResolver() : GenericCustomPostObjectTypeResolver
    {
        /** @var GenericCustomPostObjectTypeResolver */
        return $this->genericCustomPostObjectTypeResolver = $this->genericCustomPostObjectTypeResolver ?? $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    public final function setCustomPostTypeAPI($customPostTypeAPI) : void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    protected final function getCustomPostTypeAPI() : CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface $customPostObjectTypeResolverPickerRegistry
     */
    public final function setCustomPostObjectTypeResolverPickerRegistry($customPostObjectTypeResolverPickerRegistry) : void
    {
        $this->customPostObjectTypeResolverPickerRegistry = $customPostObjectTypeResolverPickerRegistry;
    }
    protected final function getCustomPostObjectTypeResolverPickerRegistry() : CustomPostObjectTypeResolverPickerRegistryInterface
    {
        /** @var CustomPostObjectTypeResolverPickerRegistryInterface */
        return $this->customPostObjectTypeResolverPickerRegistry = $this->customPostObjectTypeResolverPickerRegistry ?? $this->instanceManager->getInstance(CustomPostObjectTypeResolverPickerRegistryInterface::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getGenericCustomPostObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getCustomPostTypeAPI()->isInstanceOfCustomPostType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getCustomPostTypeAPI()->customPostExists($objectID);
    }
    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for Post or Page. Only when no other Picker is available,
     * will GenericCustomPost be used.
     */
    public function getPriorityToAttachToClasses() : int
    {
        return 0;
    }
    /**
     * Check if there are generic custom post types,
     * and only then enable it
     */
    public function isServiceEnabled() : bool
    {
        $customPostObjectTypeResolverPickers = $this->getCustomPostObjectTypeResolverPickerRegistry()->getCustomPostObjectTypeResolverPickers();
        $nonGenericCustomPostTypes = [];
        foreach ($customPostObjectTypeResolverPickers as $customPostObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($customPostObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericCustomPostTypes[] = $customPostObjectTypeResolverPicker->getCustomPostType();
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_diff($moduleConfiguration->getQueryableCustomPostTypes(), $nonGenericCustomPostTypes) !== [];
    }
    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     */
    public function getCustomPostType() : string
    {
        return '';
    }
}
