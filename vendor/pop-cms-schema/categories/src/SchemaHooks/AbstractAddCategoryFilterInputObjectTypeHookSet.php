<?php

declare (strict_types=1);
namespace PoPCMSSchema\Categories\SchemaHooks;

use PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput;
use PoPCMSSchema\Categories\FilterInputs\CategoryTaxonomyFilterInput;
use PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractAddCategoryFilterInputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput|null
     */
    private $categoryIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver|null
     */
    private $categoryTaxonomyEnumStringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\FilterInputs\CategoryTaxonomyFilterInput|null
     */
    private $categoryTaxonomyFilterInput;
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
     * @param \PoPCMSSchema\Categories\FilterInputs\CategoryIDsFilterInput $categoryIDsFilterInput
     */
    public final function setCategoryIDsFilterInput($categoryIDsFilterInput) : void
    {
        $this->categoryIDsFilterInput = $categoryIDsFilterInput;
    }
    protected final function getCategoryIDsFilterInput() : CategoryIDsFilterInput
    {
        /** @var CategoryIDsFilterInput */
        return $this->categoryIDsFilterInput = $this->categoryIDsFilterInput ?? $this->instanceManager->getInstance(CategoryIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\EnumType\CategoryTaxonomyEnumStringScalarTypeResolver $categoryTaxonomyEnumStringScalarTypeResolver
     */
    public final function setCategoryTaxonomyEnumStringScalarTypeResolver($categoryTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->categoryTaxonomyEnumStringScalarTypeResolver = $categoryTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getCategoryTaxonomyEnumStringScalarTypeResolver() : CategoryTaxonomyEnumStringScalarTypeResolver
    {
        /** @var CategoryTaxonomyEnumStringScalarTypeResolver */
        return $this->categoryTaxonomyEnumStringScalarTypeResolver = $this->categoryTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CategoryTaxonomyEnumStringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\FilterInputs\CategoryTaxonomyFilterInput $categoryTaxonomyFilterInput
     */
    public final function setCategoryTaxonomyFilterInput($categoryTaxonomyFilterInput) : void
    {
        $this->categoryTaxonomyFilterInput = $categoryTaxonomyFilterInput;
    }
    protected final function getCategoryTaxonomyFilterInput() : CategoryTaxonomyFilterInput
    {
        /** @var CategoryTaxonomyFilterInput */
        return $this->categoryTaxonomyFilterInput = $this->categoryTaxonomyFilterInput ?? $this->instanceManager->getInstance(CategoryTaxonomyFilterInput::class);
    }
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'getInputFieldDescription']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_TYPE_MODIFIERS, \Closure::fromCallable([$this, 'getInputFieldTypeModifiers']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_FILTER_INPUT, \Closure::fromCallable([$this, 'getInputFieldFilterInput']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        if (!\is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), \true)) {
            return $inputFieldNameTypeResolvers;
        }
        return \array_merge($inputFieldNameTypeResolvers, ['categoryIDs' => $this->getIDScalarTypeResolver()], $this->addCategoryTaxonomyFilterInput() ? ['categoryTaxonomy' => $this->getCategoryTaxonomyEnumStringScalarTypeResolver()] : []);
    }
    protected abstract function getInputObjectTypeResolverClass() : string;
    protected function addCategoryTaxonomyFilterInput() : bool
    {
        return \false;
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        if (!\is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), \true)) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'categoryIDs':
                return $this->__('Get results from the categories with given IDs', 'pop-users');
            case 'categoryTaxonomy':
                return $this->__('Get results from the categories with given taxonomy', 'categorys');
            default:
                return $inputFieldDescription;
        }
    }
    /**
     * @param int $inputFieldTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldTypeModifiers, $inputObjectTypeResolver, $inputFieldName) : int
    {
        if (!\is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), \true)) {
            return $inputFieldTypeModifiers;
        }
        switch ($inputFieldName) {
            case 'categoryIDs':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return $inputFieldTypeModifiers;
        }
    }
    /**
     * @param \PoP\ComponentModel\FilterInputs\FilterInputInterface|null $inputFieldFilterInput
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldFilterInput, $inputObjectTypeResolver, $inputFieldName) : ?FilterInputInterface
    {
        if (!\is_a($inputObjectTypeResolver, $this->getInputObjectTypeResolverClass(), \true)) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'categoryIDs':
                return $this->getCategoryIDsFilterInput();
            case 'categoryTaxonomy':
                return $this->getCategoryTaxonomyFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
