<?php

declare (strict_types=1);
namespace PoP\Engine\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;
interface ErrorManagerInterface
{
    /**
     * @param object $cmsError
     */
    public function convertFromCMSToPoPError($cmsError) : \PoP\ComponentModel\ErrorHandling\Error;
    /**
     * @param object $object
     */
    public function isCMSError($object) : bool;
}
