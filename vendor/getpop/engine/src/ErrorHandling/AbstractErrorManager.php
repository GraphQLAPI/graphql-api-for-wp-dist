<?php

declare (strict_types=1);
namespace PoP\Engine\ErrorHandling;

use Throwable;
abstract class AbstractErrorManager implements \PoP\Engine\ErrorHandling\ErrorManagerInterface
{
    /**
     * @param mixed $thing
     */
    public function isCMSError($thing) : bool
    {
        return $thing instanceof Throwable;
    }
}
