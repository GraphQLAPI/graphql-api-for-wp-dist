<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

class CustomPostCommentsFilterInputObjectTypeResolver extends \PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'CustomPostCommentsFilterInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Input to filter comments from custom posts', 'comments');
    }
    protected function addParentInputFields() : bool
    {
        return \true;
    }
    protected function addCustomPostInputFields() : bool
    {
        return \false;
    }
    /**
     * @return mixed
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue($inputFieldName)
    {
        switch ($inputFieldName) {
            case 'parentID':
                return 0;
            default:
                return parent::getInputFieldDefaultValue($inputFieldName);
        }
    }
}
