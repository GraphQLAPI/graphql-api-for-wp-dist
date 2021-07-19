<?php

declare (strict_types=1);
namespace PoP\CacheControl\TypeResolverDecorators;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\CacheControl\Managers\CacheControlManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\CacheControl\TypeResolverDecorators\ConfigurableCacheControlTypeResolverDecoratorTrait;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\AbstractMandatoryDirectivesForFieldsTypeResolverDecorator;
class ConfigurableCacheControlForFieldsTypeResolverDecorator extends AbstractMandatoryDirectivesForFieldsTypeResolverDecorator
{
    use ConfigurableCacheControlTypeResolverDecoratorTrait;
    /**
     * @var \PoP\CacheControl\Managers\CacheControlManagerInterface
     */
    protected $cacheControlManager;
    public function __construct(InstanceManagerInterface $instanceManager, FieldQueryInterpreterInterface $fieldQueryInterpreter, CacheControlManagerInterface $cacheControlManager)
    {
        $this->cacheControlManager = $cacheControlManager;
        parent::__construct($instanceManager, $fieldQueryInterpreter);
    }
    protected function getConfigurationEntries() : array
    {
        return $this->cacheControlManager->getEntriesForFields();
    }
}
