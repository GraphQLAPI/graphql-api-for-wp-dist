<?php

declare (strict_types=1);
namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\PathOrPathsFilterInput;
class PageByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\PathOrPathsFilterInput|null
     */
    private $pathOrPathsFilterInput;
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\PathOrPathsFilterInput $pathOrPathsFilterInput
     */
    public final function setPathOrPathsFilterInput($pathOrPathsFilterInput) : void
    {
        $this->pathOrPathsFilterInput = $pathOrPathsFilterInput;
    }
    protected final function getPathOrPathsFilterInput() : PathOrPathsFilterInput
    {
        /** @var PathOrPathsFilterInput */
        return $this->pathOrPathsFilterInput = $this->pathOrPathsFilterInput ?? $this->instanceManager->getInstance(PathOrPathsFilterInput::class);
    }
    public function getTypeName() : string
    {
        return 'PageByInput';
    }
    protected function getTypeDescriptionCustomPostEntity() : string
    {
        return $this->__('a page', 'pages');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return \array_merge(parent::getInputFieldNameTypeResolvers(), ['path' => $this->getStringScalarTypeResolver()]);
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'path':
                return $this->__('Query by page path', 'pages');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldName) : ?FilterInputInterface
    {
        switch ($inputFieldName) {
            case 'path':
                return $this->getPathOrPathsFilterInput();
            default:
                return parent::getInputFieldFilterInput($inputFieldName);
        }
    }
}
