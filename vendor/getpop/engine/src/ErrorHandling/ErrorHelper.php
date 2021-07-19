<?php

declare (strict_types=1);
namespace PoP\Engine\ErrorHandling;

class ErrorHelper implements \PoP\Engine\ErrorHandling\ErrorHelperInterface
{
    /**
     * @var \PoP\Engine\ErrorHandling\ErrorManagerInterface
     */
    protected $errorManager;
    public function __construct(\PoP\Engine\ErrorHandling\ErrorManagerInterface $errorManager)
    {
        $this->errorManager = $errorManager;
    }
    /**
     * @param mixed $result
     * @return mixed
     */
    public function returnResultOrConvertError($result)
    {
        if ($this->errorManager->isCMSError($result)) {
            return $this->errorManager->convertFromCMSToPoPError((object) $result);
        }
        return $result;
    }
}
