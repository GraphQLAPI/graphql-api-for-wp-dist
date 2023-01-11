<?php

declare (strict_types=1);
namespace PoP\Engine\ObjectModels;

class SuperRoot
{
    public const ID = 'super-root';
    public function getID() : string
    {
        return self::ID;
    }
}
