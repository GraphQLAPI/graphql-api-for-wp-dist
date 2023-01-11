<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Comments\Constants\CommentOrderBy;
use PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;
class CommentSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver|null
     */
    private $customPostSortByEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\EnumType\CommentOrderByEnumTypeResolver $customPostSortByEnumTypeResolver
     */
    public final function setCommentOrderByEnumTypeResolver($customPostSortByEnumTypeResolver) : void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    protected final function getCommentOrderByEnumTypeResolver() : CommentOrderByEnumTypeResolver
    {
        /** @var CommentOrderByEnumTypeResolver */
        return $this->customPostSortByEnumTypeResolver = $this->customPostSortByEnumTypeResolver ?? $this->instanceManager->getInstance(CommentOrderByEnumTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'CommentSortInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['by' => $this->getCommentOrderByEnumTypeResolver()]);
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'by':
                return CommentOrderBy::DATE;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
