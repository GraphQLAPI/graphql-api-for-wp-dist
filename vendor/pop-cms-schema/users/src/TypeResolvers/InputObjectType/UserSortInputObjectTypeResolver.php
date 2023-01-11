<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoPCMSSchema\Users\Constants\UserOrderBy;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\SortInputObjectTypeResolver;
class UserSortInputObjectTypeResolver extends SortInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver|null
     */
    private $customPostSortByEnumTypeResolver;
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver $customPostSortByEnumTypeResolver
     */
    public final function setUserOrderByEnumTypeResolver($customPostSortByEnumTypeResolver) : void
    {
        $this->customPostSortByEnumTypeResolver = $customPostSortByEnumTypeResolver;
    }
    protected final function getUserOrderByEnumTypeResolver() : UserOrderByEnumTypeResolver
    {
        /** @var UserOrderByEnumTypeResolver */
        return $this->customPostSortByEnumTypeResolver = $this->customPostSortByEnumTypeResolver ?? $this->instanceManager->getInstance(UserOrderByEnumTypeResolver::class);
    }
    public function getTypeName() : string
    {
        return 'UserSortInput';
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['by' => $this->getUserOrderByEnumTypeResolver()]);
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'by':
                return UserOrderBy::ID;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
