<?php

declare (strict_types=1);
namespace PoP\Root\State;

use PoP\Root\Services\BasicServiceTrait;
use PoP\Root\Services\ServiceTrait;
abstract class AbstractAppStateProvider implements \PoP\Root\State\AppStateProviderInterface
{
    use BasicServiceTrait;
    use ServiceTrait;
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state) : void
    {
    }
    /**
     * @param array<string,mixed> $state
     */
    public function consolidate(&$state) : void
    {
    }
    /**
     * @param array<string,mixed> $state
     */
    public function augment(&$state) : void
    {
    }
    /**
     * @param array<string,mixed> $state
     */
    public function compute(&$state) : void
    {
    }
    /**
     * @param array<string,mixed> $state
     */
    public function execute(&$state) : void
    {
    }
}
