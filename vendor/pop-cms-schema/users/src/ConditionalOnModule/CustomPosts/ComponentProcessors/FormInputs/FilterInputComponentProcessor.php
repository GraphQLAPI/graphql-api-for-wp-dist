<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_AUTHOR_IDS = 'filterinput-author-ids';
    public const COMPONENT_FILTERINPUT_AUTHOR_SLUG = 'filterinput-author-slug';
    public const COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS = 'filterinput-exclude-author-ids';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput|null
     */
    private $authorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput|null
     */
    private $authorSlugFilterInput;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput|null
     */
    private $excludeAuthorIDsFilterInput;
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
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput $authorIDsFilterInput
     */
    public final function setAuthorIDsFilterInput($authorIDsFilterInput) : void
    {
        $this->authorIDsFilterInput = $authorIDsFilterInput;
    }
    protected final function getAuthorIDsFilterInput() : AuthorIDsFilterInput
    {
        /** @var AuthorIDsFilterInput */
        return $this->authorIDsFilterInput = $this->authorIDsFilterInput ?? $this->instanceManager->getInstance(AuthorIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput $authorSlugFilterInput
     */
    public final function setAuthorSlugFilterInput($authorSlugFilterInput) : void
    {
        $this->authorSlugFilterInput = $authorSlugFilterInput;
    }
    protected final function getAuthorSlugFilterInput() : AuthorSlugFilterInput
    {
        /** @var AuthorSlugFilterInput */
        return $this->authorSlugFilterInput = $this->authorSlugFilterInput ?? $this->instanceManager->getInstance(AuthorSlugFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput $excludeAuthorIDsFilterInput
     */
    public final function setExcludeAuthorIDsFilterInput($excludeAuthorIDsFilterInput) : void
    {
        $this->excludeAuthorIDsFilterInput = $excludeAuthorIDsFilterInput;
    }
    protected final function getExcludeAuthorIDsFilterInput() : ExcludeAuthorIDsFilterInput
    {
        /** @var ExcludeAuthorIDsFilterInput */
        return $this->excludeAuthorIDsFilterInput = $this->excludeAuthorIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeAuthorIDsFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_AUTHOR_IDS, self::COMPONENT_FILTERINPUT_AUTHOR_SLUG, self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_AUTHOR_IDS:
                return $this->getAuthorIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_AUTHOR_SLUG:
                return $this->getAuthorSlugFilterInput();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                return $this->getExcludeAuthorIDsFilterInput();
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_AUTHOR_IDS:
                return 'authorIDs';
            case self::COMPONENT_FILTERINPUT_AUTHOR_SLUG:
                return 'authorSlug';
            case self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                return 'excludeAuthorIDs';
            default:
                return parent::getName($component);
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeResolver($component) : InputTypeResolverInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_AUTHOR_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_AUTHOR_SLUG:
                return $this->getStringScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                return $this->getIDScalarTypeResolver();
            default:
                return $this->getDefaultSchemaFilterInputTypeResolver();
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputTypeModifiers($component) : int
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_AUTHOR_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return SchemaTypeModifiers::NONE;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_AUTHOR_IDS:
                return $this->__('Get results from the authors with given IDs', 'pop-users');
            case self::COMPONENT_FILTERINPUT_AUTHOR_SLUG:
                return $this->__('Get results from the authors with given slug', 'pop-users');
            case self::COMPONENT_FILTERINPUT_EXCLUDE_AUTHOR_IDS:
                return $this->__('Get results excluding the ones from authors with given IDs', 'pop-users');
            default:
                return null;
        }
    }
}
