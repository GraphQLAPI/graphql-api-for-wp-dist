<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use Throwable;

abstract class AbstractErrorManager implements ErrorManagerInterface
{
    /**
     * @param object $object
     */
    public function isCMSError($object): bool
    {
        return $object instanceof Throwable;
    }
}
