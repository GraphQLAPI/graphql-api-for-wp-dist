<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Schema;

interface TypeCastingExecuterInterface
{
    /**
     * Cast the value to the indicated type, or return null or Error (with a message) if it fails
     * @param mixed $value
     * @return mixed
     */
    public function cast(string $type, $value);
}
