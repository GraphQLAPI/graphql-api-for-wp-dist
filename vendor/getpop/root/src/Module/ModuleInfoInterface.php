<?php

declare (strict_types=1);
namespace PoP\Root\Module;

interface ModuleInfoInterface
{
    /**
     * @return mixed
     * @param string $key
     */
    public function get($key);
}
