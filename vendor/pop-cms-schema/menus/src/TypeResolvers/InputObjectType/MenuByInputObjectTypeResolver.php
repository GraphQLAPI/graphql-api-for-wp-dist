<?php

declare (strict_types=1);
namespace PoPCMSSchema\Menus\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
class MenuByInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
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
    public function getTypeName() : string
    {
        return 'MenuByInput';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a menu', 'menus');
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers() : array
    {
        return ['id' => $this->getIDScalarTypeResolver()];
    }
    /**
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldName) : ?string
    {
        switch ($inputFieldName) {
            case 'id':
                return $this->__('Query by menu ID', 'menus');
            default:
                return parent::getInputFieldDescription($inputFieldName);
        }
    }
}
