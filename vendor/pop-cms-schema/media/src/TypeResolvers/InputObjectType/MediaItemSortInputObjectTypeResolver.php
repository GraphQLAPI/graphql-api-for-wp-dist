<?php

declare (strict_types=1);
namespace PoPCMSSchema\Media\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Media\Constants\MediaItemOrderBy;
use PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;
class MediaItemSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver|null
     */
    private $customPostSortByEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\EnumType\MediaItemOrderByEnumTypeResolver $customPostSortByEnumTypeResolver
     */
    public final function setMediaItemOrderByEnumTypeResolver($customPostSortByEnumTypeResolver) : void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    protected final function getMediaItemOrderByEnumTypeResolver() : MediaItemOrderByEnumTypeResolver
    {
        /** @var MediaItemOrderByEnumTypeResolver */
        return $this->customPostSortByEnumTypeResolver = $this->customPostSortByEnumTypeResolver ?? $this->instanceManager->getInstance(MediaItemOrderByEnumTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'MediaItemSortInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['by' => $this->getMediaItemOrderByEnumTypeResolver()]);
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'by':
                return MediaItemOrderBy::DATE;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
