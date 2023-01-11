<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\ObjectTypeResolverPickers;

use PoPCMSSchema\Tags\Module;
use PoPCMSSchema\Tags\ModuleConfiguration;
use PoPCMSSchema\Tags\Registries\TagObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractGenericTagObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements \PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface
{
    /**
     * @var string[]|null
     */
    protected $genericTagTaxonomies;
    /**
     * @var string[]|null
     */
    protected $nonGenericTagTaxonomies;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver|null
     */
    private $genericTagObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface|null
     */
    private $queryableTagTypeAPI;
    /**
     * @var \PoPCMSSchema\Tags\Registries\TagObjectTypeResolverPickerRegistryInterface|null
     */
    private $tagObjectTypeResolverPickerRegistry;
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver $genericTagObjectTypeResolver
     */
    public final function setGenericTagObjectTypeResolver($genericTagObjectTypeResolver) : void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    protected final function getGenericTagObjectTypeResolver() : GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver = $this->genericTagObjectTypeResolver ?? $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeAPIs\QueryableTagTypeAPIInterface $queryableTagTypeAPI
     */
    public final function setQueryableTagTypeAPI($queryableTagTypeAPI) : void
    {
        $this->queryableTagTypeAPI = $queryableTagTypeAPI;
    }
    protected final function getQueryableTagTypeAPI() : QueryableTagTypeAPIInterface
    {
        /** @var QueryableTagTypeAPIInterface */
        return $this->queryableTagTypeAPI = $this->queryableTagTypeAPI ?? $this->instanceManager->getInstance(QueryableTagTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\Registries\TagObjectTypeResolverPickerRegistryInterface $tagObjectTypeResolverPickerRegistry
     */
    public final function setTagObjectTypeResolverPickerRegistry($tagObjectTypeResolverPickerRegistry) : void
    {
        $this->tagObjectTypeResolverPickerRegistry = $tagObjectTypeResolverPickerRegistry;
    }
    protected final function getTagObjectTypeResolverPickerRegistry() : TagObjectTypeResolverPickerRegistryInterface
    {
        /** @var TagObjectTypeResolverPickerRegistryInterface */
        return $this->tagObjectTypeResolverPickerRegistry = $this->tagObjectTypeResolverPickerRegistry ?? $this->instanceManager->getInstance(TagObjectTypeResolverPickerRegistryInterface::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getGenericTagObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getQueryableTagTypeAPI()->isInstanceOfTagType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getQueryableTagTypeAPI()->tagExists($objectID);
    }
    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for PostTag. Only when no other Picker is available,
     * will GenericTag be used.
     */
    public function getPriorityToAttachToClasses() : int
    {
        return 0;
    }
    /**
     * Check if there are generic tag taxonomies,
     * and only then enable it
     */
    public function isServiceEnabled() : bool
    {
        return $this->getGenericTagTaxonomies() !== [];
    }
    /**
     * @return string[]
     */
    protected function getGenericTagTaxonomies() : array
    {
        if ($this->genericTagTaxonomies === null) {
            $this->genericTagTaxonomies = $this->doGetGenericTagTaxonomies();
        }
        return $this->genericTagTaxonomies;
    }
    /**
     * @return string[]
     */
    protected function doGetGenericTagTaxonomies() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_diff($moduleConfiguration->getQueryableTagTaxonomies(), $this->getNonGenericTagTaxonomies());
    }
    /**
     * @return string[]
     */
    protected function getNonGenericTagTaxonomies() : array
    {
        if ($this->nonGenericTagTaxonomies === null) {
            $this->nonGenericTagTaxonomies = $this->doGetNonGenericTagTaxonomies();
        }
        return $this->nonGenericTagTaxonomies;
    }
    /**
     * @return string[]
     */
    protected function doGetNonGenericTagTaxonomies() : array
    {
        $tagObjectTypeResolverPickers = $this->getTagObjectTypeResolverPickerRegistry()->getTagObjectTypeResolverPickers();
        $nonGenericTagTaxonomies = [];
        foreach ($tagObjectTypeResolverPickers as $tagObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($tagObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericTagTaxonomies[] = $tagObjectTypeResolverPicker->getTagTaxonomy();
        }
        return $nonGenericTagTaxonomies;
    }
    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     */
    public function getTagTaxonomy() : string
    {
        return '';
    }
}
