<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\FieldResolvers\InterfaceType;

use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentPaginationInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\InterfaceType\CommentableInterfaceTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
class CommentableInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver|null
     */
    private $intScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentsFilterInputObjectTypeResolver|null
     */
    private $customPostCommentsFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentPaginationInputObjectTypeResolver|null
     */
    private $customPostCommentPaginationInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CommentSortInputObjectTypeResolver|null
     */
    private $commentSortInputObjectTypeResolver;
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
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentsFilterInputObjectTypeResolver $customPostCommentsFilterInputObjectTypeResolver
     */
    public final function setCustomPostCommentsFilterInputObjectTypeResolver($customPostCommentsFilterInputObjectTypeResolver) : void
    {
        $this->customPostCommentsFilterInputObjectTypeResolver = $customPostCommentsFilterInputObjectTypeResolver;
    }
    protected final function getCustomPostCommentsFilterInputObjectTypeResolver() : CustomPostCommentsFilterInputObjectTypeResolver
    {
        /** @var CustomPostCommentsFilterInputObjectTypeResolver */
        return $this->customPostCommentsFilterInputObjectTypeResolver = $this->customPostCommentsFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostCommentsFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\CustomPostCommentPaginationInputObjectTypeResolver $customPostCommentPaginationInputObjectTypeResolver
     */
    public final function setCustomPostCommentPaginationInputObjectTypeResolver($customPostCommentPaginationInputObjectTypeResolver) : void
    {
        $this->customPostCommentPaginationInputObjectTypeResolver = $customPostCommentPaginationInputObjectTypeResolver;
    }
    protected final function getCustomPostCommentPaginationInputObjectTypeResolver() : CustomPostCommentPaginationInputObjectTypeResolver
    {
        /** @var CustomPostCommentPaginationInputObjectTypeResolver */
        return $this->customPostCommentPaginationInputObjectTypeResolver = $this->customPostCommentPaginationInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostCommentPaginationInputObjectTypeResolver::class);
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
     * @return array<class-string<InterfaceTypeResolverInterface>>
     */
    public function getInterfaceTypeResolverClassesToAttachTo() : array
    {
        return [CommentableInterfaceTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToImplement() : array
    {
        return ['areCommentsOpen', 'hasComments', 'commentCount', 'comments'];
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeResolver($fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'comments':
                return $this->getCommentObjectTypeResolver();
            case 'areCommentsOpen':
                return $this->getBooleanScalarTypeResolver();
            case 'hasComments':
                return $this->getBooleanScalarTypeResolver();
            case 'commentCount':
                return $this->getIntScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($fieldName) : int
    {
        switch ($fieldName) {
            case 'areCommentsOpen':
            case 'hasComments':
            case 'commentCount':
                return SchemaTypeModifiers::NON_NULLABLE;
            case 'comments':
                return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return parent::getFieldTypeModifiers($fieldName);
        }
    }
    /**
     * @param string $fieldName
     */
    public function getFieldDescription($fieldName) : ?string
    {
        switch ($fieldName) {
            case 'areCommentsOpen':
                return $this->__('Are comments open to be added to the custom post', 'pop-comments');
            case 'hasComments':
                return $this->__('Does the custom post have comments?', 'pop-comments');
            case 'commentCount':
                return $this->__('Number of comments added to the custom post', 'pop-comments');
            case 'comments':
                return $this->__('Comments added to the custom post', 'pop-comments');
            default:
                return parent::getFieldDescription($fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($fieldName) : array
    {
        $fieldArgNameTypeResolvers = parent::getFieldArgNameTypeResolvers($fieldName);
        switch ($fieldName) {
            case 'comments':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCustomPostCommentsFilterInputObjectTypeResolver(), 'pagination' => $this->getCustomPostCommentPaginationInputObjectTypeResolver(), 'sort' => $this->getCommentSortInputObjectTypeResolver()]);
            case 'commentCount':
                return \array_merge($fieldArgNameTypeResolvers, ['filter' => $this->getCustomPostCommentsFilterInputObjectTypeResolver()]);
            default:
                return $fieldArgNameTypeResolvers;
        }
    }
}
