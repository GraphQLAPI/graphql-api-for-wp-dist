<?php

declare (strict_types=1);
namespace PoP\Root\StateManagers;

use PoP\Root\Exception\AppStateNotExistsException;
interface AppStateManagerInterface
{
    /**
     * Called by the AppLoader to initalize the state.
     *
     * Initialize application state
     *
     * @param array<string,mixed> $initialAppState
     */
    public function initializeAppState($initialAppState) : void;
    /**
     * Called by the AppLoader to "boot" the state.
     *
     * Execute application state
     */
    public function executeAppState() : void;
    /**
     * @return array<string,mixed>
     */
    public function all() : array;
    /**
     * To be called by Engine. Use with care!
     * @param mixed $value
     * @param string $key
     */
    public function override($key, $value) : void;
    /**
     * @throws AppStateNotExistsException If there is no state under the provided key
     * @return mixed
     * @param string $key
     */
    public function get($key);
    /**
     * @throws AppStateNotExistsException If there is no state under the provided path
     * @param string[] $path
     * @return mixed
     */
    public function getUnder($path);
    /**
     * @param string $key
     */
    public function has($key) : bool;
    /**
     * @param string[] $path
     */
    public function hasUnder($path) : bool;
}
