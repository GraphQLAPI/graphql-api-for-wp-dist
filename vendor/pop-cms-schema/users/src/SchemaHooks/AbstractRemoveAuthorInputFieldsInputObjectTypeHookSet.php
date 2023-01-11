<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\Hooks\AbstractHookSet;
abstract class AbstractRemoveAuthorInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    use \PoPCMSSchema\Users\SchemaHooks\AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
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
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']), 100, 2);
    }
    /**
     * Indicate if to remove the fields added by the SchemaHookSet
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected abstract function removeAuthorInputFields($inputObjectTypeResolver) : bool;
    /**
     * Remove the fields added by the SchemaHookSet
     *
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        if (!$this->removeAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $authorInputFieldNames = \array_keys($this->getAuthorInputFieldNameTypeResolvers());
        foreach ($authorInputFieldNames as $authorInputFieldName) {
            unset($inputFieldNameTypeResolvers[$authorInputFieldName]);
        }
        return $inputFieldNameTypeResolvers;
    }
}
