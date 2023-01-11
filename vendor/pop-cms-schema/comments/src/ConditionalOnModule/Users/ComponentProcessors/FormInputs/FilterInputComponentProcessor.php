<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\ComponentProcessors\FormInputs;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS = 'filterinput-custompost-author-ids';
    public const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS = 'filterinput-exclude-custompost-author-ids';
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput|null
     */
    private $customPostAuthorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput|null
     */
    private $excludeCustomPostAuthorIDsFilterInput;
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
     * @param \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput
     */
    public final function setCustomPostAuthorIDsFilterInput($customPostAuthorIDsFilterInput) : void
    {
        $this->customPostAuthorIDsFilterInput = $customPostAuthorIDsFilterInput;
    }
    protected final function getCustomPostAuthorIDsFilterInput() : CustomPostAuthorIDsFilterInput
    {
        /** @var CustomPostAuthorIDsFilterInput */
        return $this->customPostAuthorIDsFilterInput = $this->customPostAuthorIDsFilterInput ?? $this->instanceManager->getInstance(CustomPostAuthorIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput
     */
    public final function setExcludeCustomPostAuthorIDsFilterInput($excludeCustomPostAuthorIDsFilterInput) : void
    {
        $this->excludeCustomPostAuthorIDsFilterInput = $excludeCustomPostAuthorIDsFilterInput;
    }
    protected final function getExcludeCustomPostAuthorIDsFilterInput() : ExcludeCustomPostAuthorIDsFilterInput
    {
        /** @var ExcludeCustomPostAuthorIDsFilterInput */
        return $this->excludeCustomPostAuthorIDsFilterInput = $this->excludeCustomPostAuthorIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeCustomPostAuthorIDsFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS, self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
                return $this->getCustomPostAuthorIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
                return $this->getExcludeCustomPostAuthorIDsFilterInput();
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
                return 'customPostAuthorIDs';
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
                return 'excludeCustomPostAuthorIDs';
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_AUTHOR_IDS:
                return $this->__('Get results from the authors with given IDs', 'pop-users');
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_AUTHOR_IDS:
                return $this->__('Get results from the ones from authors with given IDs', 'pop-users');
            default:
                return null;
        }
    }
}
