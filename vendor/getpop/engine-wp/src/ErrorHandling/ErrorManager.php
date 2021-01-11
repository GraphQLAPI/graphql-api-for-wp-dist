<?php

declare(strict_types=1);

namespace PoP\EngineWP\ErrorHandling;

use PoP\Engine\ErrorHandling\AbstractErrorManager;
use PoP\ComponentModel\ErrorHandling\Error;
use WP_Error;

class ErrorManager extends AbstractErrorManager
{
    /**
     * @param object $cmsError
     */
    public function convertFromCMSToPoPError($cmsError): Error
    {
        $error = new Error();
        /** @var WP_Error */
        $cmsError = $cmsError;
        foreach ($cmsError->get_error_codes() as $code) {
            $error->add($code, $cmsError->get_error_message($code), $cmsError->get_error_data($code));
        }
        return $error;
    }

    /**
     * @param object $object
     */
    public function isCMSError($object): bool
    {
        return \is_wp_error($object);
    }
}
