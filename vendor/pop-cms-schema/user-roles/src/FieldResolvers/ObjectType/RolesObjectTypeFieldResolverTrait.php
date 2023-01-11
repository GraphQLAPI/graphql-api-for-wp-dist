<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserRoles\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Translation\TranslationAPIInterface;
use PoPCMSSchema\UserRoles\Module;
use PoPCMSSchema\UserRoles\ModuleConfiguration;
trait RolesObjectTypeFieldResolverTrait
{
    protected abstract function getTranslationAPI() : TranslationAPIInterface;
    protected abstract function getStringScalarTypeResolver() : StringScalarTypeResolver;
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['roles', 'capabilities'];
    }
    /**
     * @return string[]
     */
    public function getSensitiveFieldNames() : array
    {
        $sensitiveFieldNames = parent::getSensitiveFieldNames();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserRoleAsSensitiveData()) {
            $sensitiveFieldNames[] = 'roles';
        }
        if ($moduleConfiguration->treatUserCapabilityAsSensitiveData()) {
            $sensitiveFieldNames[] = 'capabilities';
        }
        return $sensitiveFieldNames;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'roles':
                return $this->getStringScalarTypeResolver();
            case 'capabilities':
                return $this->getStringScalarTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'roles':
                return $this->getTranslationAPI()->__('All user roles', 'user-roles');
            case 'capabilities':
                return $this->getTranslationAPI()->__('All user capabilities', 'user-roles');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
}
