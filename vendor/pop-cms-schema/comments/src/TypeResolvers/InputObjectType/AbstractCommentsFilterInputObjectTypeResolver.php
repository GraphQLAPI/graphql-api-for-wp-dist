<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoPCMSSchema\Comments\Constants\CommentStatus;
use PoPCMSSchema\Comments\Constants\CommentTypes;
use PoPCMSSchema\Comments\FilterInputs\CommentStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CommentTypesFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostIDsFilterInput;
use PoPCMSSchema\Comments\FilterInputs\CustomPostStatusFilterInput;
use PoPCMSSchema\Comments\FilterInputs\ExcludeCustomPostIDsFilterInput;
use PoPCMSSchema\Comments\Module;
use PoPCMSSchema\Comments\ModuleConfiguration;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput;
use PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\AbstractObjectsFilterInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
abstract class AbstractCommentsFilterInputObjectTypeResolver extends AbstractObjectsFilterInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver|null
     */
    private $dateQueryInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver|null
     */
    private $commentStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostStatusEnumTypeResolver|null
     */
    private $customPostStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentTypeEnumTypeResolver|null
     */
    private $commentTypeEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver|null
     */
    private $customPostEnumStringScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
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
     * @var \PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput|null
     */
    private $unionCustomPostTypesFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput|null
     */
    private $searchFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput|null
     */
    private $parentIDFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput|null
     */
    private $parentIDsFilterInput;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput|null
     */
    private $excludeParentIDsFilterInput;
    /**
     * @param \PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\DateQueryInputObjectTypeResolver $dateQueryInputObjectTypeResolver
     */
    public final function setDateQueryInputObjectTypeResolver($dateQueryInputObjectTypeResolver) : void
    {
        $this->dateQueryInputObjectTypeResolver = $dateQueryInputObjectTypeResolver;
    }
    protected final function getDateQueryInputObjectTypeResolver() : DateQueryInputObjectTypeResolver
    {
        /** @var DateQueryInputObjectTypeResolver */
        return $this->dateQueryInputObjectTypeResolver = $this->dateQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(DateQueryInputObjectTypeResolver::class);
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
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\CustomPostEnumStringScalarTypeResolver $customPostEnumStringScalarTypeResolver
     */
    public final function setCustomPostEnumStringScalarTypeResolver($customPostEnumStringScalarTypeResolver) : void
    {
        $this->customPostEnumStringScalarTypeResolver = $customPostEnumStringScalarTypeResolver;
    }
    protected final function getCustomPostEnumStringScalarTypeResolver() : CustomPostEnumStringScalarTypeResolver
    {
        /** @var CustomPostEnumStringScalarTypeResolver */
        return $this->customPostEnumStringScalarTypeResolver = $this->customPostEnumStringScalarTypeResolver ?? $this->instanceManager->getInstance(CustomPostEnumStringScalarTypeResolver::class);
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
     * @param \PoPCMSSchema\CustomPosts\FilterInputs\UnionCustomPostTypesFilterInput $unionCustomPostTypesFilterInput
     */
    public final function setUnionCustomPostTypesFilterInput($unionCustomPostTypesFilterInput) : void
    {
        $this->unionCustomPostTypesFilterInput = $unionCustomPostTypesFilterInput;
    }
    protected final function getUnionCustomPostTypesFilterInput() : UnionCustomPostTypesFilterInput
    {
        /** @var UnionCustomPostTypesFilterInput */
        return $this->unionCustomPostTypesFilterInput = $this->unionCustomPostTypesFilterInput ?? $this->instanceManager->getInstance(UnionCustomPostTypesFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ExcludeParentIDsFilterInput $excludeParentIDsFilterInput
     */
    public final function setExcludeParentIDsFilterInput($excludeParentIDsFilterInput) : void
    {
        $this->excludeParentIDsFilterInput = $excludeParentIDsFilterInput;
    }
    protected final function getExcludeParentIDsFilterInput() : ExcludeParentIDsFilterInput
    {
        /** @var ExcludeParentIDsFilterInput */
        return $this->excludeParentIDsFilterInput = $this->excludeParentIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeParentIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SearchFilterInput $searchFilterInput
     */
    public final function setSearchFilterInput($searchFilterInput) : void
    {
        $this->searchFilterInput = $searchFilterInput;
    }
    protected final function getSearchFilterInput() : SearchFilterInput
    {
        /** @var SearchFilterInput */
        return $this->searchFilterInput = $this->searchFilterInput ?? $this->instanceManager->getInstance(SearchFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDFilterInput $parentIDFilterInput
     */
    public final function setParentIDFilterInput($parentIDFilterInput) : void
    {
        $this->parentIDFilterInput = $parentIDFilterInput;
    }
    protected final function getParentIDFilterInput() : ParentIDFilterInput
    {
        /** @var ParentIDFilterInput */
        return $this->parentIDFilterInput = $this->parentIDFilterInput ?? $this->instanceManager->getInstance(ParentIDFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\ParentIDsFilterInput $parentIDsFilterInput
     */
    public final function setParentIDsFilterInput($parentIDsFilterInput) : void
    {
        $this->parentIDsFilterInput = $parentIDsFilterInput;
    }
    protected final function getParentIDsFilterInput() : ParentIDsFilterInput
    {
        /** @var ParentIDsFilterInput */
        return $this->parentIDsFilterInput = $this->parentIDsFilterInput ?? $this->instanceManager->getInstance(ParentIDsFilterInput::class);
    }
    /**
     * @return string[]
     */
    public function getSensitiveInputFieldNames() : array
    {
        $adminInputFieldNames = parent::getSensitiveInputFieldNames();
        if ($this->treatCommentStatusAsSensitiveData()) {
            $adminInputFieldNames[] = 'status';
        }
        if ($this->treatCustomPostStatusAsSensitiveData()) {
            $adminInputFieldNames[] = 'customPostStatus';
        }
        return $adminInputFieldNames;
    }
    protected function treatCommentStatusAsSensitiveData() : bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->treatCommentStatusAsSensitiveData();
    }
    protected function treatCustomPostStatusAsSensitiveData() : bool
    {
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        return $moduleConfiguration->treatCustomPostStatusAsSensitiveData();
    }
    protected abstract function addParentInputFields() : bool;
    protected abstract function addCustomPostInputFields() : bool;
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['status' => $this->getCommentStatusEnumTypeResolver(), 'search' => $this->getStringScalarTypeResolver(), 'dateQuery' => $this->getDateQueryInputObjectTypeResolver(), 'types' => $this->getCommentTypeEnumTypeResolver()], $this->addParentInputFields() ? ['parentID' => $this->getIDScalarTypeResolver(), 'parentIDs' => $this->getIDScalarTypeResolver(), 'excludeParentIDs' => $this->getIDScalarTypeResolver()] : [], $this->addCustomPostInputFields() ? ['customPostID' => $this->getIDScalarTypeResolver(), 'customPostIDs' => $this->getIDScalarTypeResolver(), 'excludeCustomPostIDs' => $this->getIDScalarTypeResolver(), 'customPostStatus' => $this->getCustomPostStatusEnumTypeResolver(), 'customPostTypes' => $this->getCustomPostEnumStringScalarTypeResolver()] : []);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'status':
                return $this->__('Comment status', 'comments');
            case 'search':
                return $this->__('Search for comments containing the given string', 'comments');
            case 'dateQuery':
                return $this->__('Filter comments based on date', 'comments');
            case 'types':
                return $this->__('Filter comments based on type', 'comments');
            case 'parentID':
                return $this->__('Filter comments with the given parent IDs. \'0\' means \'no parent\'', 'comments');
            case 'parentIDs':
                return $this->__('Filter comments with the given parent ID. \'0\' means \'no parent\'', 'comments');
            case 'excludeParentIDs':
                return $this->__('Exclude comments with the given parent IDs', 'comments');
            case 'customPostID':
                return $this->__('Filter comments added to the given custom post', 'comments');
            case 'customPostIDs':
                return $this->__('Filter comments added to the given custom posts', 'comments');
            case 'excludeCustomPostIDs':
                return $this->__('Exclude comments added to the given custom posts', 'comments');
            case 'customPostStatus':
                return $this->__('Filter comments added to the custom posts with given status', 'comments');
            case 'customPostTypes':
                return $this->__('Filter comments added to custom posts of given types', 'comments');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'status':
                return [CommentStatus::APPROVE];
            case 'types':
                return [CommentTypes::COMMENT];
            case 'customPostStatus':
                return [CustomPostStatus::PUBLISH];
            case 'customPostTypes':
                return $this->getCustomPostEnumStringScalarTypeResolver()->getConsolidatedPossibleValues();
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldName) : int
    {
        switch ($inputFieldName) {
            case 'status':
            case 'types':
            case 'parentIDs':
            case 'excludeParentIDs':
            case 'customPostIDs':
            case 'excludeCustomPostIDs':
            case 'customPostStatus':
            case 'customPostTypes':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getInputFieldTypeModifiers($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'status':
                return $this->getCommentStatusFilterInput();
            case 'search':
                return $this->getSearchFilterInput();
            case 'types':
                return $this->getCommentTypesFilterInput();
            case 'parentID':
                return $this->getParentIDFilterInput();
            case 'parentIDs':
                return $this->getParentIDsFilterInput();
            case 'excludeParentIDs':
                return $this->getExcludeParentIDsFilterInput();
            case 'customPostID':
                return $this->getCustomPostIDFilterInput();
            case 'customPostIDs':
                return $this->getCustomPostIDsFilterInput();
            case 'excludeCustomPostIDs':
                return $this->getExcludeCustomPostIDsFilterInput();
            case 'customPostStatus':
                return $this->getCustomPostStatusFilterInput();
            case 'customPostTypes':
                return $this->getUnionCustomPostTypesFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
