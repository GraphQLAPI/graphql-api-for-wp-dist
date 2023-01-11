<?php

declare (strict_types=1);
namespace PoPCMSSchema\Tags\SchemaHooks;

use PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput;
use PoPCMSSchema\Tags\FilterInputs\TagTaxonomyFilterInput;
use PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractAddTagFilterInputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput|null
     */
    private $tagSlugsFilterInput;
    /**
     * @var \PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput|null
     */
    private $tagIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver|null
     */
    private $tagTaxonomyEnumStringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\FilterInputs\TagTaxonomyFilterInput|null
     */
    private $tagTaxonomyFilterInput;
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
     * @param \PoPCMSSchema\Tags\FilterInputs\TagSlugsFilterInput $tagSlugsFilterInput
     */
    public final function setTagSlugsFilterInput($tagSlugsFilterInput) : void
    {
        $this->tagSlugsFilterInput = $tagSlugsFilterInput;
    }
    protected final function getTagSlugsFilterInput() : TagSlugsFilterInput
    {
        /** @var TagSlugsFilterInput */
        return $this->tagSlugsFilterInput = $this->tagSlugsFilterInput ?? $this->instanceManager->getInstance(TagSlugsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\FilterInputs\TagIDsFilterInput $tagIDsFilterInput
     */
    public final function setTagIDsFilterInput($tagIDsFilterInput) : void
    {
        $this->tagIDsFilterInput = $tagIDsFilterInput;
    }
    protected final function getTagIDsFilterInput() : TagIDsFilterInput
    {
        /** @var TagIDsFilterInput */
        return $this->tagIDsFilterInput = $this->tagIDsFilterInput ?? $this->instanceManager->getInstance(TagIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\EnumType\TagTaxonomyEnumStringScalarTypeResolver $tagTaxonomyEnumStringScalarTypeResolver
     */
    public final function setTagTaxonomyEnumStringScalarTypeResolver($tagTaxonomyEnumStringScalarTypeResolver) : void
    {
        $this->tagTaxonomyEnumStringScalarTypeResolver = $tagTaxonomyEnumStringScalarTypeResolver;
    }
    protected final function getTagTaxonomyEnumStringScalarTypeResolver() : TagTaxonomyEnumStringScalarTypeResolver
    {
        /** @var TagTaxonomyEnumStringScalarTypeResolver */
        return $this->tagTaxonomyEnumStringScalarTypeResolver = $this->tagTaxonomyEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(TagTaxonomyEnumStringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\FilterInputs\TagTaxonomyFilterInput $tagTaxonomyFilterInput
     */
    public final function setTagTaxonomyFilterInput($tagTaxonomyFilterInput) : void
    {
        $this->tagTaxonomyFilterInput = $tagTaxonomyFilterInput;
    }
    protected final function getTagTaxonomyFilterInput() : TagTaxonomyFilterInput
    {
        /** @var TagTaxonomyFilterInput */
        return $this->tagTaxonomyFilterInput = $this->tagTaxonomyFilterInput ?? $this->instanceManager->getInstance(TagTaxonomyFilterInput::class);
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
        return \array_merge($inputFieldNameTypeResolvers, ['tagIDs' => $this->getIDScalarTypeResolver(), 'tagSlugs' => $this->getStringScalarTypeResolver()], $this->addTagTaxonomyFilterInput() ? ['tagTaxonomy' => $this->getTagTaxonomyEnumStringScalarTypeResolver()] : []);
    }
    protected abstract function getInputObjectTypeResolverClass() : string;
    protected function addTagTaxonomyFilterInput() : bool
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
            case 'tagIDs':
                return $this->__('Get results from the tags with given IDs', 'tags');
            case 'tagSlugs':
                return $this->__('Get results from the tags with given slug', 'tags');
            case 'tagTaxonomy':
                return $this->__('Get results from the tags with given taxonomy', 'tags');
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
            case 'tagIDs':
            case 'tagSlugs':
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
            case 'tagIDs':
                return $this->getTagIDsFilterInput();
            case 'tagSlugs':
                return $this->getTagSlugsFilterInput();
            case 'tagTaxonomy':
                return $this->getTagTaxonomyFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
