<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\ObjectTypeResolverPickers;

use PoPCMSSchema\Categories\Module;
use PoPCMSSchema\Categories\ModuleConfiguration;
use PoPCMSSchema\Categories\Registries\CategoryObjectTypeResolverPickerRegistryInterface;
use PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractGenericCategoryObjectTypeResolverPicker extends AbstractObjectTypeResolverPicker implements \PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface
{
    /**
     * @var string[]|null
     */
    protected $genericCategoryTaxonomies;
    /**
     * @var string[]|null
     */
    protected $nonGenericCategoryTaxonomies;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver|null
     */
    private $genericCategoryObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface|null
     */
    private $queryableCategoryTypeAPI;
    /**
     * @var \PoPCMSSchema\Categories\Registries\CategoryObjectTypeResolverPickerRegistryInterface|null
     */
    private $categoryObjectTypeResolverPickerRegistry;
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver
     */
    public final function setGenericCategoryObjectTypeResolver($genericCategoryObjectTypeResolver) : void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    protected final function getGenericCategoryObjectTypeResolver() : GenericCategoryObjectTypeResolver
    {
        /** @var GenericCategoryObjectTypeResolver */
        return $this->genericCategoryObjectTypeResolver = $this->genericCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeAPIs\QueryableCategoryTypeAPIInterface $queryableCategoryTypeAPI
     */
    public final function setQueryableCategoryTypeAPI($queryableCategoryTypeAPI) : void
    {
        $this->queryableCategoryTypeAPI = $queryableCategoryTypeAPI;
    }
    protected final function getQueryableCategoryTypeAPI() : QueryableCategoryTypeAPIInterface
    {
        /** @var QueryableCategoryTypeAPIInterface */
        return $this->queryableCategoryTypeAPI = $this->queryableCategoryTypeAPI ?? $this->instanceManager->getInstance(QueryableCategoryTypeAPIInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\Registries\CategoryObjectTypeResolverPickerRegistryInterface $categoryObjectTypeResolverPickerRegistry
     */
    public final function setCategoryObjectTypeResolverPickerRegistry($categoryObjectTypeResolverPickerRegistry) : void
    {
        $this->categoryObjectTypeResolverPickerRegistry = $categoryObjectTypeResolverPickerRegistry;
    }
    protected final function getCategoryObjectTypeResolverPickerRegistry() : CategoryObjectTypeResolverPickerRegistryInterface
    {
        /** @var CategoryObjectTypeResolverPickerRegistryInterface */
        return $this->categoryObjectTypeResolverPickerRegistry = $this->categoryObjectTypeResolverPickerRegistry ?? $this->instanceManager->getInstance(CategoryObjectTypeResolverPickerRegistryInterface::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getGenericCategoryObjectTypeResolver();
    }
    /**
     * @param object $object
     */
    public function isInstanceOfType($object) : bool
    {
        return $this->getQueryableCategoryTypeAPI()->isInstanceOfCategoryType($object);
    }
    /**
     * @param string|int $objectID
     */
    public function isIDOfType($objectID) : bool
    {
        return $this->getQueryableCategoryTypeAPI()->categoryExists($objectID);
    }
    /**
     * Process last, as to allow specific Pickers to take precedence,
     * such as for PostCategory. Only when no other Picker is available,
     * will GenericCategory be used.
     */
    public function getPriorityToAttachToClasses() : int
    {
        return 0;
    }
    /**
     * Check if there are generic category taxonomies,
     * and only then enable it
     */
    public function isServiceEnabled() : bool
    {
        return $this->getGenericCategoryTaxonomies() !== [];
    }
    /**
     * @return string[]
     */
    protected function getGenericCategoryTaxonomies() : array
    {
        if ($this->genericCategoryTaxonomies === null) {
            $this->genericCategoryTaxonomies = $this->doGetGenericCategoryTaxonomies();
        }
        return $this->genericCategoryTaxonomies;
    }
    /**
     * @return string[]
     */
    protected function doGetGenericCategoryTaxonomies() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_diff($moduleConfiguration->getQueryableCategoryTaxonomies(), $this->getNonGenericCategoryTaxonomies());
    }
    /**
     * @return string[]
     */
    protected function getNonGenericCategoryTaxonomies() : array
    {
        if ($this->nonGenericCategoryTaxonomies === null) {
            $this->nonGenericCategoryTaxonomies = $this->doGetNonGenericCategoryTaxonomies();
        }
        return $this->nonGenericCategoryTaxonomies;
    }
    /**
     * @return string[]
     */
    protected function doGetNonGenericCategoryTaxonomies() : array
    {
        $categoryObjectTypeResolverPickers = $this->getCategoryObjectTypeResolverPickerRegistry()->getCategoryObjectTypeResolverPickers();
        $nonGenericCategoryTaxonomies = [];
        foreach ($categoryObjectTypeResolverPickers as $categoryObjectTypeResolverPicker) {
            // Skip this class, we're interested in all the non-generic ones
            if ($categoryObjectTypeResolverPicker === $this) {
                continue;
            }
            $nonGenericCategoryTaxonomies[] = $categoryObjectTypeResolverPicker->getCategoryTaxonomy();
        }
        return $nonGenericCategoryTaxonomies;
    }
    /**
     * Return empty value is OK, because this method will
     * never be called on this class.
     *
     * @see `isServiceEnabled`
     */
    public function getCategoryTaxonomy() : string
    {
        return '';
    }
}
