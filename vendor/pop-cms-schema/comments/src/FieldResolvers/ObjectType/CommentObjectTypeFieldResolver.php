<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\FieldResolvers\ObjectType;

use DateTime;
use PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsePaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsesFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\SchemaCommons\ComponentProcessors\CommonFilterInputContainerComponentProcessor;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
class CommentObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface|null
     */
    private $commentTypeAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver|null
     */
    private $htmlScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver|null
     */
    private $urlScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver|null
     */
    private $emailScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver|null
     */
    private $dateTimeScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver|null
     */
    private $commentStatusEnumTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface|null
     */
    private $dateFormatter;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsesFilterInputObjectTypeResolver|null
     */
    private $commentResponsesFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsePaginationInputObjectTypeResolver|null
     */
    private $commentResponsePaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver|null
     */
    private $commentSortInputObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\Comments\TypeAPIs\CommentTypeAPIInterface $commentTypeAPI
     */
    public final function setCommentTypeAPI($commentTypeAPI) : void
    {
        $this->commentTypeAPI = $commentTypeAPI;
    }
    protected final function getCommentTypeAPI() : CommentTypeAPIInterface
    {
        /** @var CommentTypeAPIInterface */
        return $this->commentTypeAPI = $this->commentTypeAPI ?? $this->instanceManager->getInstance(CommentTypeAPIInterface::class);
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
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver $htmlScalarTypeResolver
     */
    public final function setHTMLScalarTypeResolver($htmlScalarTypeResolver) : void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    protected final function getHTMLScalarTypeResolver() : HTMLScalarTypeResolver
    {
        /** @var HTMLScalarTypeResolver */
        return $this->htmlScalarTypeResolver = $this->htmlScalarTypeResolver ?? $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver $urlScalarTypeResolver
     */
    public final function setURLScalarTypeResolver($urlScalarTypeResolver) : void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    protected final function getURLScalarTypeResolver() : URLScalarTypeResolver
    {
        /** @var URLScalarTypeResolver */
        return $this->urlScalarTypeResolver = $this->urlScalarTypeResolver ?? $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver $emailScalarTypeResolver
     */
    public final function setEmailScalarTypeResolver($emailScalarTypeResolver) : void
    {
        $this->emailScalarTypeResolver = $emailScalarTypeResolver;
    }
    protected final function getEmailScalarTypeResolver() : EmailScalarTypeResolver
    {
        /** @var EmailScalarTypeResolver */
        return $this->emailScalarTypeResolver = $this->emailScalarTypeResolver ?? $this->instanceManager->getInstance(EmailScalarTypeResolver::class);
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    public final function setBooleanScalarTypeResolver($booleanScalarTypeResolver) : void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    protected final function getBooleanScalarTypeResolver() : BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateTimeScalarTypeResolver $dateTimeScalarTypeResolver
     */
    public final function setDateTimeScalarTypeResolver($dateTimeScalarTypeResolver) : void
    {
        $this->dateTimeScalarTypeResolver = $dateTimeScalarTypeResolver;
    }
    protected final function getDateTimeScalarTypeResolver() : DateTimeScalarTypeResolver
    {
        /** @var DateTimeScalarTypeResolver */
        return $this->dateTimeScalarTypeResolver = $this->dateTimeScalarTypeResolver ?? $this->instanceManager->getInstance(DateTimeScalarTypeResolver::class);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver $intScalarTypeResolver
     */
    public final function setIntScalarTypeResolver($intScalarTypeResolver) : void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    protected final function getIntScalarTypeResolver() : IntScalarTypeResolver
    {
        /** @var IntScalarTypeResolver */
        return $this->intScalarTypeResolver = $this->intScalarTypeResolver ?? $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver $commentObjectTypeResolver
     */
    public final function setCommentObjectTypeResolver($commentObjectTypeResolver) : void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    protected final function getCommentObjectTypeResolver() : CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver = $this->commentObjectTypeResolver ?? $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
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
     * @param \PoPCMSSchema\SchemaCommons\Formatters\DateFormatterInterface $dateFormatter
     */
    public final function setDateFormatter($dateFormatter) : void
    {
        $this->dateFormatter = $dateFormatter;
    }
    protected final function getDateFormatter() : DateFormatterInterface
    {
        /** @var DateFormatterInterface */
        return $this->dateFormatter = $this->dateFormatter ?? $this->instanceManager->getInstance(DateFormatterInterface::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsesFilterInputObjectTypeResolver $commentResponsesFilterInputObjectTypeResolver
     */
    public final function setCommentResponsesFilterInputObjectTypeResolver($commentResponsesFilterInputObjectTypeResolver) : void
    {
        $this->commentResponsesFilterInputObjectTypeResolver = $commentResponsesFilterInputObjectTypeResolver;
    }
    protected final function getCommentResponsesFilterInputObjectTypeResolver() : CommentResponsesFilterInputObjectTypeResolver
    {
        /** @var CommentResponsesFilterInputObjectTypeResolver */
        return $this->commentResponsesFilterInputObjectTypeResolver = $this->commentResponsesFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentResponsesFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentResponsePaginationInputObjectTypeResolver $commentResponsePaginationInputObjectTypeResolver
     */
    public final function setCommentResponsePaginationInputObjectTypeResolver($commentResponsePaginationInputObjectTypeResolver) : void
    {
        $this->commentResponsePaginationInputObjectTypeResolver = $commentResponsePaginationInputObjectTypeResolver;
    }
    protected final function getCommentResponsePaginationInputObjectTypeResolver() : CommentResponsePaginationInputObjectTypeResolver
    {
        /** @var CommentResponsePaginationInputObjectTypeResolver */
        return $this->commentResponsePaginationInputObjectTypeResolver = $this->commentResponsePaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentResponsePaginationInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver $commentSortInputObjectTypeResolver
     */
    public final function setCommentSortInputObjectTypeResolver($commentSortInputObjectTypeResolver) : void
    {
        $this->commentSortInputObjectTypeResolver = $commentSortInputObjectTypeResolver;
    }
    protected final function getCommentSortInputObjectTypeResolver() : CommentSortInputObjectTypeResolver
    {
        /** @var CommentSortInputObjectTypeResolver */
        return $this->commentSortInputObjectTypeResolver = $this->commentSortInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentSortInputObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [CommentObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['customPost', 'customPostID', 'content', 'rawContent', 'authorName', 'authorURL', 'authorEmail', 'approved', 'type', 'status', 'parent', 'date', 'dateStr', 'responses', 'responseCount'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'rawContent':
            case 'authorName':
            case 'type':
            case 'dateStr':
                return $this->getStringScalarTypeResolver();
            case 'content':
                return $this->getHTMLScalarTypeResolver();
            case 'authorURL':
                return $this->getURLScalarTypeResolver();
            case 'authorEmail':
                return $this->getEmailScalarTypeResolver();
            case 'customPostID':
                return $this->getIDScalarTypeResolver();
            case 'approved':
                return $this->getBooleanScalarTypeResolver();
            case 'date':
                return $this->getDateTimeScalarTypeResolver();
            case 'responseCount':
                return $this->getIntScalarTypeResolver();
            case 'customPost':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver();
            case 'parent':
            case 'responses':
                return $this->getCommentObjectTypeResolver();
            case 'status':
                return $this->getCommentStatusEnumTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'content':
            case 'rawContent':
            case 'customPost':
            case 'customPostID':
            case 'approved':
            case 'type':
            case 'status':
            case 'date':
            case 'dateStr':
            case 'responseCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'responses':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'content':
                return $this->__('Comment\'s content, in HTML format', 'pop-comments');
            case 'rawContent':
                return $this->__('Comment\'s content, in raw format', 'pop-comments');
            case 'authorName':
                return $this->__('Comment author\'s name', 'pop-comments');
            case 'authorURL':
                return $this->__('Comment author\'s URL', 'pop-comments');
            case 'authorEmail':
                return $this->__('Comment author\'s email', 'pop-comments');
            case 'customPost':
                return $this->__('Custom post to which the comment was added', 'pop-comments');
            case 'customPostID':
                return $this->__('ID of the custom post to which the comment was added', 'pop-comments');
            case 'approved':
                return $this->__('Is the comment approved?', 'pop-comments');
            case 'type':
                return $this->__('Type of comment', 'pop-comments');
            case 'status':
                return $this->__('Status of the comment', 'pop-comments');
            case 'parent':
                return $this->__('Parent comment (if this comment is a response to another one)', 'pop-comments');
            case 'date':
                return $this->__('Date when the comment was added', 'pop-comments');
            case 'dateStr':
                return $this->__('Date when the comment was added, in String format', 'pop-comments');
            case 'responses':
                return $this->__('Responses to the comment', 'pop-comments');
            case 'responseCount':
                return $this->__('Number of responses to the comment', 'pop-comments');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName) : ?Component
    {
        switch ($fieldName) {
            case 'date':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE);
            case 'dateStr':
                return new Component(CommonFilterInputContainerComponentProcessor::class, CommonFilterInputContainerComponentProcessor::COMPONENT_FILTERINPUTCONTAINER_GMTDATE_AS_STRING);
            default:
                return parent::getFieldFilterInputContainerComponent($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        switch ($fieldName) {
            case 'responses':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCommentResponsesFilterInputObjectTypeResolver(), 'pagination' => $this->getCommentResponsePaginationInputObjectTypeResolver(), 'sort' => $this->getCommentSortInputObjectTypeResolver()]);
            case 'responseCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCommentResponsesFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
        }
    }
    /**
     * @return mixed
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore)
    {
        $comment = $object;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'content':
                return $this->getCommentTypeAPI()->getCommentContent($comment);
            case 'rawContent':
                return $this->getCommentTypeAPI()->getCommentRawContent($comment);
            case 'authorName':
                return $this->getCommentTypeAPI()->getCommentAuthorName($comment);
            case 'authorURL':
                return $this->getCommentTypeAPI()->getCommentAuthorURL($comment);
            case 'authorEmail':
                return $this->getCommentTypeAPI()->getCommentAuthorEmail($comment);
            case 'customPost':
            case 'customPostID':
                return $this->getCommentTypeAPI()->getCommentPostID($comment);
            case 'approved':
                return $this->getCommentTypeAPI()->isCommentApproved($comment);
            case 'type':
                return $this->getCommentTypeAPI()->getCommentType($comment);
            case 'status':
                return $this->getCommentTypeAPI()->getCommentStatus($comment);
            case 'parent':
                return $this->getCommentTypeAPI()->getCommentParent($comment);
            case 'date':
                /** @var string */
                $date = $this->getCommentTypeAPI()->getCommentDate($comment, $fieldDataAccessor->getValue('gmt'));
                return new DateTime($date);
            case 'dateStr':
                /** @var string */
                $date = $this->getCommentTypeAPI()->getCommentDate($comment, $fieldDataAccessor->getValue('gmt'));
                return $this->getDateFormatter()->format($fieldDataAccessor->getValue('format'), $date);
        }
        $query = \array_merge($this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldDataAccessor), ['parent-id' => $objectTypeResolver->getID($comment)]);
        switch ($fieldDataAccessor->getFieldName()) {
            case 'responses':
                return $this->getCommentTypeAPI()->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'responseCount':
                return $this->getCommentTypeAPI()->getCommentCount($query);
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}
