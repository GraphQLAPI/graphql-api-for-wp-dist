<?php

declare (strict_types=1);
namespace PoP\Root\StateManagers;

use PoPBackbone\PHPHooks\PHPHooks;
class HookManager implements \PoP\Root\StateManagers\HookManagerInterface
{
    /**
     * @var \PoPBackbone\PHPHooks\PHPHooks
     */
    protected $phpHooks;
    public function __construct()
    {
        $this->phpHooks = new PHPHooks();
        /**
         * Copied from bainternet/php-hooks/php-hooks.php
         *
         * @see https://github.com/bainternet/PHP-Hooks/blob/7b28d10ed7a2f7e3c8bd7f53ba1e9b4769955242/php-hooks.php#L562
         */
        $this->phpHooks->do_action('After_Hooks_Setup', $this->phpHooks);
    }
    /**
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public function addFilter($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        $this->phpHooks->add_filter($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public function removeFilter($tag, $function_to_remove, $priority = 10) : bool
    {
        return $this->phpHooks->remove_filter($tag, $function_to_remove, $priority);
    }
    /**
     * @param mixed $value
     * @param mixed ...$args
     * @return mixed
     * @param string $tag
     */
    public function applyFilters($tag, $value, ...$args)
    {
        return $this->phpHooks->apply_filters($tag, $value, ...$args);
    }
    /**
     * @param string $tag
     * @param callable $function_to_add
     * @param int $priority
     * @param int $accepted_args
     */
    public function addAction($tag, $function_to_add, $priority = 10, $accepted_args = 1) : void
    {
        $this->phpHooks->add_action($tag, $function_to_add, $priority, $accepted_args);
    }
    /**
     * @param string $tag
     * @param callable $function_to_remove
     * @param int $priority
     */
    public function removeAction($tag, $function_to_remove, $priority = 10) : bool
    {
        return $this->phpHooks->remove_action($tag, $function_to_remove, $priority);
    }
    /**
     * @param mixed ...$args
     * @param string $tag
     */
    public function doAction($tag, ...$args) : void
    {
        $this->phpHooks->do_action($tag, ...$args);
    }
}
