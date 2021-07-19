<?php

declare (strict_types=1);
namespace PoP\Root\Component;

trait ComponentInfoTrait
{
    /** @var array<string, mixed> */
    private static $container = [];
    /**
     * @var bool
     */
    private static $initialized = \false;
    /**
     * @param array<string, mixed> $container
     */
    public static function init(array $container) : void
    {
        if (self::$initialized) {
            return;
        }
        self::$initialized = \true;
        self::$container = $container;
    }
    /**
     * @return mixed
     */
    public static function get(string $key)
    {
        return self::$container[$key] ?? null;
    }
}
