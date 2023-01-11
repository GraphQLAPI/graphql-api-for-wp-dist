<?php

declare (strict_types=1);
namespace PoP\Root\StateManagers;

interface HookManagerInterface
{
    /**
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void;
    /**
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public function removeFilter($tag, $function_to_remove, $priority = 10) : bool;
    /**
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     * @param string $tag
     */
    public function applyFilters($tag, $value, ...$args);
    /**
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void;
    /**
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public function removeAction($tag, $function_to_remove, $priority = 10) : bool;
    /**
     * @param mixed ...$args
     * @param string $tag
     */
    public function doAction($tag, ...$args) : void;
}
