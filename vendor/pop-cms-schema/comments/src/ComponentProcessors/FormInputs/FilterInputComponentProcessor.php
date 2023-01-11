<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ComponentProcessors\FormInputs;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoP\ComponentModel\ComponentProcessors\AbstractFilterInputComponentProcessor;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\FormInputs\FormMultipleInput;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
class FilterInputComponentProcessor extends AbstractFilterInputComponentProcessor implements DataloadQueryArgsFilterInputComponentProcessorInterface
{
    public const COMPONENT_FILTERINPUT_CUSTOMPOST_IDS = 'filterinput-custompost-ids';
    public const COMPONENT_FILTERINPUT_CUSTOMPOST_ID = 'filterinput-custompost-id';
    public const COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS = 'filterinput-custompost-status';
    public const COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS = 'filterinput-exclude-custompost-ids';
    public const COMPONENT_FILTERINPUT_COMMENT_TYPES = 'filterinput-comment-types';
    public const COMPONENT_FILTERINPUT_COMMENT_STATUS = 'filterinput-comment-status';
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver|null
     */
    private $commentTypeEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver|null
     */
    private $commentStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver|null
     */
    private $customPostStatusEnumTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput|null
     */
    private $commentStatusFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput|null
     */
    private $commentTypesFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput|null
     */
    private $customPostIDFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput|null
     */
    private $customPostIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput|null
     */
    private $customPostStatusFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput|null
     */
    private $excludeCustomPostIDsFilterInput;
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver $commentTypeEnumTypeResolver
     */
    public final function setCommentTypeEnumTypeResolver($commentTypeEnumTypeResolver) : void
    {
        $this->commentTypeEnumTypeResolver = $commentTypeEnumTypeResolver;
    }
    protected final function getCommentTypeEnumTypeResolver() : CommentTypeEnumTypeResolver
    {
        /** @var CommentTypeEnumTypeResolver */
        return $this->commentTypeEnumTypeResolver = $this->commentTypeEnumTypeResolver ?? $this->instanceManager->getInstance(CommentTypeEnumTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver
     */
    public final function setCommentStatusEnumTypeResolver($commentStatusEnumTypeResolver) : void
    {
        $this->commentStatusEnumTypeResolver = $commentStatusEnumTypeResolver;
    }
    protected final function getCommentStatusEnumTypeResolver() : CommentStatusEnumTypeResolver
    {
        /** @var CommentStatusEnumTypeResolver */
        return $this->commentStatusEnumTypeResolver = $this->commentStatusEnumTypeResolver ?? $this->instanceManager->getInstance(CommentStatusEnumTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver $customPostStatusEnumTypeResolver
     */
    public final function setCustomPostStatusEnumTypeResolver($customPostStatusEnumTypeResolver) : void
    {
        $this->customPostStatusEnumTypeResolver = $customPostStatusEnumTypeResolver;
    }
    protected final function getCustomPostStatusEnumTypeResolver() : CustomPostStatusEnumTypeResolver
    {
        /** @var CustomPostStatusEnumTypeResolver */
        return $this->customPostStatusEnumTypeResolver = $this->customPostStatusEnumTypeResolver ?? $this->instanceManager->getInstance(CustomPostStatusEnumTypeResolver::class);
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
     * @param \PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput $commentStatusFilterInput
     */
    public final function setCommentStatusFilterInput($commentStatusFilterInput) : void
    {
        $this->commentStatusFilterInput = $commentStatusFilterInput;
    }
    protected final function getCommentStatusFilterInput() : CommentStatusFilterInput
    {
        /** @var CommentStatusFilterInput */
        return $this->commentStatusFilterInput = $this->commentStatusFilterInput ?? $this->instanceManager->getInstance(CommentStatusFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput $commentTypesFilterInput
     */
    public final function setCommentTypesFilterInput($commentTypesFilterInput) : void
    {
        $this->commentTypesFilterInput = $commentTypesFilterInput;
    }
    protected final function getCommentTypesFilterInput() : CommentTypesFilterInput
    {
        /** @var CommentTypesFilterInput */
        return $this->commentTypesFilterInput = $this->commentTypesFilterInput ?? $this->instanceManager->getInstance(CommentTypesFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput $customPostIDFilterInput
     */
    public final function setCustomPostIDFilterInput($customPostIDFilterInput) : void
    {
        $this->customPostIDFilterInput = $customPostIDFilterInput;
    }
    protected final function getCustomPostIDFilterInput() : CustomPostIDFilterInput
    {
        /** @var CustomPostIDFilterInput */
        return $this->customPostIDFilterInput = $this->customPostIDFilterInput ?? $this->instanceManager->getInstance(CustomPostIDFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput $customPostIDsFilterInput
     */
    public final function setCustomPostIDsFilterInput($customPostIDsFilterInput) : void
    {
        $this->customPostIDsFilterInput = $customPostIDsFilterInput;
    }
    protected final function getCustomPostIDsFilterInput() : CustomPostIDsFilterInput
    {
        /** @var CustomPostIDsFilterInput */
        return $this->customPostIDsFilterInput = $this->customPostIDsFilterInput ?? $this->instanceManager->getInstance(CustomPostIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput $customPostStatusFilterInput
     */
    public final function setCustomPostStatusFilterInput($customPostStatusFilterInput) : void
    {
        $this->customPostStatusFilterInput = $customPostStatusFilterInput;
    }
    protected final function getCustomPostStatusFilterInput() : CustomPostStatusFilterInput
    {
        /** @var CustomPostStatusFilterInput */
        return $this->customPostStatusFilterInput = $this->customPostStatusFilterInput ?? $this->instanceManager->getInstance(CustomPostStatusFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput $excludeCustomPostIDsFilterInput
     */
    public final function setExcludeCustomPostIDsFilterInput($excludeCustomPostIDsFilterInput) : void
    {
        $this->excludeCustomPostIDsFilterInput = $excludeCustomPostIDsFilterInput;
    }
    protected final function getExcludeCustomPostIDsFilterInput() : ExcludeCustomPostIDsFilterInput
    {
        /** @var ExcludeCustomPostIDsFilterInput */
        return $this->excludeCustomPostIDsFilterInput = $this->excludeCustomPostIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeCustomPostIDsFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS, self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID, self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS, self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS, self::COMPONENT_FILTERINPUT_COMMENT_TYPES, self::COMPONENT_FILTERINPUT_COMMENT_STATUS);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInput($component) : ?FilterInputInterface
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
                return $this->getCustomPostIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID:
                return $this->getCustomPostIDFilterInput();
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
                return $this->getCustomPostStatusFilterInput();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                return $this->getExcludeCustomPostIDsFilterInput();
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
                return $this->getCommentTypesFilterInput();
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return $this->getCommentStatusFilterInput();
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getInputClass($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return FormMultipleInput::class;
        }
        return parent::getInputClass($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getName($component) : string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
                return 'customPostIDs';
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID:
                return 'customPostID';
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
                return 'customPostStatus';
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                return 'excludeCustomPostIDs';
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
                return 'types';
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return 'status';
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
                return $this->getCustomPostStatusEnumTypeResolver();
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                return $this->getIDScalarTypeResolver();
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
                return $this->getCommentTypeEnumTypeResolver();
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return $this->getCommentStatusEnumTypeResolver();
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
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return SchemaTypeModifiers::NONE;
        }
    }
    /**
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDefaultValue($component)
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
                return [CustomPostStatus::PUBLISH];
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
                return [CommentTypes::COMMENT];
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return [CommentStatus::APPROVE];
            default:
                return null;
        }
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputDescription($component) : ?string
    {
        switch ($component->name) {
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS:
                return $this->__('Limit results to elements with the given custom post IDs', 'comments');
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_ID:
                return $this->__('Limit results to elements with the given custom post ID', 'comments');
            case self::COMPONENT_FILTERINPUT_CUSTOMPOST_STATUS:
                return $this->__('Limit results to elements with the given custom post status', 'comments');
            case self::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS:
                return $this->__('Exclude elements with the given custom post IDs', 'comments');
            case self::COMPONENT_FILTERINPUT_COMMENT_TYPES:
                return $this->__('Types of comment', 'comments');
            case self::COMPONENT_FILTERINPUT_COMMENT_STATUS:
                return $this->__('Status of the comment', 'comments');
            default:
                return null;
        }
    }
}
