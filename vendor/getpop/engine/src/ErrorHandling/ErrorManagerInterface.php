<?php

declare (strict_types=1);
namespace PoP\Engine\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;
interface ErrorManagerInterface
{
    /**
     * @param object $cmsError
     */
    public function convertFromCMSToPoPError($cmsError) : Error;
    /**
     * @param mixed $thing
     */
    public function isCMSError($thing) : bool;
}
