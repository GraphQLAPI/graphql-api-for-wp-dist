<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoPCMSSchema\Comments\ComponentProcessors\FormInputs\FilterInputComponentProcessor;
use PoPCMSSchema\CustomPosts\ComponentProcessors\FormInputs\FilterInputComponentProcessor as CustomPostFilterInputComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\AbstractFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\FormInputs\CommonFilterInputComponentProcessor;
class CommentFilterInputContainerComponentProcessor extends AbstractFilterInputContainerComponentProcessor
{
    public const HOOK_FILTER_INPUTS = __CLASS__ . ':filter-inputs';
    public const COMPONENT_FILTERINPUTCONTAINER_COMMENTS = 'filterinputcontainer-comments';
    public const COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT = 'filterinputcontainer-commentcount';
    public const COMPONENT_FILTERINPUTCONTAINER_RESPONSES = 'filterinputcontainer-responses';
    public const COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT = 'filterinputcontainer-responsecount';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS = 'filterinputcontainer-custompost-comments';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT = 'filterinputcontainer-custompost-commentcount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS = 'filterinputcontainer-admincomments';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT = 'filterinputcontainer-admincommentcount';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES = 'filterinputcontainer-adminresponses';
    public const COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT = 'filterinputcontainer-adminresponsecount';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS = 'filterinputcontainer-custompost-admincomments';
    public const COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT = 'filterinputcontainer-custompost-admincommentcount';
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array
    {
        return array(self::COMPONENT_FILTERINPUTCONTAINER_COMMENTS, self::COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_RESPONSES, self::COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS, self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT, self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES, self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS, self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterInputComponents($component) : array
    {
        $item0Unpacked = $this->getIDFilterInputComponents();
        $responseFilterInputComponents = \array_merge($item0Unpacked, [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_SEARCH), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_TYPES)]);
        $customPostCommentFilterInputComponents = \array_merge(\is_array($responseFilterInputComponents) ? $responseFilterInputComponents : \iterator_to_array($responseFilterInputComponents), [new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_ID), new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_PARENT_IDS), new Component(CommonFilterInputComponentProcessor::class, CommonFilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_PARENT_IDS)]);
        $rootCommentFilterInputComponents = \array_merge(\is_array($customPostCommentFilterInputComponents) ? $customPostCommentFilterInputComponents : \iterator_to_array($customPostCommentFilterInputComponents), [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_ID), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_CUSTOMPOST_IDS), new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_EXCLUDE_CUSTOMPOST_IDS), new Component(CustomPostFilterInputComponentProcessor::class, CustomPostFilterInputComponentProcessor::COMPONENT_FILTERINPUT_UNIONCUSTOMPOSTTYPES)]);
        $adminCommentFilterInputComponents = [new Component(FilterInputComponentProcessor::class, FilterInputComponentProcessor::COMPONENT_FILTERINPUT_COMMENT_STATUS)];
        $paginationFilterInputComponents = $this->getPaginationFilterInputComponents();
        switch ((string) $component->name) {
            case self::COMPONENT_FILTERINPUTCONTAINER_RESPONSECOUNT:
                return $responseFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_RESPONSES:
                return \array_merge(\is_array($responseFilterInputComponents) ? $responseFilterInputComponents : \iterator_to_array($responseFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT:
                return $customPostCommentFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS:
                return \array_merge(\is_array($customPostCommentFilterInputComponents) ? $customPostCommentFilterInputComponents : \iterator_to_array($customPostCommentFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_COMMENTCOUNT:
                return $rootCommentFilterInputComponents;
            case self::COMPONENT_FILTERINPUTCONTAINER_COMMENTS:
                return \array_merge(\is_array($rootCommentFilterInputComponents) ? $rootCommentFilterInputComponents : \iterator_to_array($rootCommentFilterInputComponents), $paginationFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSECOUNT:
                return \array_merge(\is_array($responseFilterInputComponents) ? $responseFilterInputComponents : \iterator_to_array($responseFilterInputComponents), $adminCommentFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINRESPONSES:
                return \array_merge(\is_array($responseFilterInputComponents) ? $responseFilterInputComponents : \iterator_to_array($responseFilterInputComponents), $paginationFilterInputComponents, $adminCommentFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT:
                return \array_merge(\is_array($customPostCommentFilterInputComponents) ? $customPostCommentFilterInputComponents : \iterator_to_array($customPostCommentFilterInputComponents), $adminCommentFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS:
                return \array_merge(\is_array($customPostCommentFilterInputComponents) ? $customPostCommentFilterInputComponents : \iterator_to_array($customPostCommentFilterInputComponents), $paginationFilterInputComponents, $adminCommentFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT:
                return \array_merge(\is_array($rootCommentFilterInputComponents) ? $rootCommentFilterInputComponents : \iterator_to_array($rootCommentFilterInputComponents), $adminCommentFilterInputComponents);
            case self::COMPONENT_FILTERINPUTCONTAINER_ADMINCOMMENTS:
                return \array_merge(\is_array($rootCommentFilterInputComponents) ? $rootCommentFilterInputComponents : \iterator_to_array($rootCommentFilterInputComponents), $paginationFilterInputComponents, $adminCommentFilterInputComponents);
            default:
                return [];
        }
    }
    /**
     * @return string[]
     */
    protected function getFilterInputHookNames() : array
    {
        $item1Unpacked = parent::getFilterInputHookNames();
        return \array_merge($item1Unpacked, [self::HOOK_FILTER_INPUTS]);
    }
}
