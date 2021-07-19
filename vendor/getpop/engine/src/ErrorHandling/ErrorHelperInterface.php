<?php

declare (strict_types=1);
namespace PoP\Engine\ErrorHandling;

interface ErrorHelperInterface
{
    /**
     * @param mixed $result
     * @return mixed
     */
    public function returnResultOrConvertError($result);
}
