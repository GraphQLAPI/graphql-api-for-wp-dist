<?php

declare (strict_types=1);
namespace PoP\ComponentModel\FieldInterfaceResolvers;

use PoP\Hooks\HooksAPIInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\Translation\TranslationAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceSchemaDefinitionResolverTrait;
abstract class AbstractFieldInterfaceResolver implements \PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface
{
    use FieldInterfaceSchemaDefinitionResolverTrait;
    /**
     * @var \PoP\Translation\TranslationAPIInterface
     */
    protected $translationAPI;
    /**
     * @var \PoP\Hooks\HooksAPIInterface
     */
    protected $hooksAPI;
    /**
     * @var \PoP\ComponentModel\Instances\InstanceManagerInterface
     */
    protected $instanceManager;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface
     */
    protected $nameResolver;
    /**
     * @var \PoP\Engine\CMS\CMSServiceInterface
     */
    protected $cmsService;
    public function __construct(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, NameResolverInterface $nameResolver, CMSServiceInterface $cmsService)
    {
        $this->translationAPI = $translationAPI;
        $this->hooksAPI = $hooksAPI;
        $this->instanceManager = $instanceManager;
        $this->nameResolver = $nameResolver;
        $this->cmsService = $cmsService;
    }
    public function getFieldNamesToResolve() : array
    {
        return $this->getFieldNamesToImplement();
    }
    public function getImplementedFieldInterfaceResolverClasses() : array
    {
        return [];
    }
    public function getNamespace() : string
    {
        return SchemaHelpers::getSchemaNamespace(\get_called_class());
    }
    public final function getNamespacedInterfaceName() : string
    {
        return SchemaHelpers::getSchemaNamespacedName($this->getNamespace(), $this->getInterfaceName());
    }
    public final function getMaybeNamespacedInterfaceName() : string
    {
        $vars = ApplicationState::getVars();
        return $vars['namespace-types-and-interfaces'] ? $this->getNamespacedInterfaceName() : $this->getInterfaceName();
    }
    public function getSchemaInterfaceDescription() : ?string
    {
        return null;
    }
    // public function getSchemaInterfaceVersion(string $fieldName): ?string
    // {
    //     return null;
    // }
    /**
     * This function is not called by the engine, to generate the schema.
     * Instead, the resolver is obtained from the fieldResolver.
     * To make sure that all fieldResolvers implementing the same interface
     * return the expected type for the field, they can obtain it from the
     * interface through this function.
     */
    public function getFieldTypeResolverClass(string $fieldName) : ?string
    {
        return null;
    }
}
