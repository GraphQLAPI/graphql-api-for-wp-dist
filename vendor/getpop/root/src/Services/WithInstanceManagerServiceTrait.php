<?php

declare (strict_types=1);
namespace PoP\Root\Services;

use PoP\Root\Instances\InstanceManagerInterface;
use PrefixedByPoP\Symfony\Contracts\Service\Attribute\Required;
trait WithInstanceManagerServiceTrait
{
    /**
     * @var \PoP\Root\Instances\InstanceManagerInterface
     */
    protected $instanceManager;
    /**
     * @required
     */
    public final function setInstanceManager(InstanceManagerInterface $instanceManager) : void
    {
        $this->instanceManager = $instanceManager;
    }
    protected final function getInstanceManager() : InstanceManagerInterface
    {
        return $this->instanceManager;
    }
}
