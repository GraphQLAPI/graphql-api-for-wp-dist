<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\Exception;

use PoP\Root\Exception\AbstractClientException;
use stdClass;
use Throwable;
/**
 * Retrieve extra information when passing the exception.
 *
 * Useful for passing extra data to a ObjectMutationPayload type,
 * instead of printing the error under `errors`
 */
abstract class AbstractPayloadClientException extends AbstractClientException
{
    /**
     * @var int|string|null
     */
    public $errorCode = null;
    /**
     * @var \stdClass|null
     */
    public $data;
    /**
     * @param int|string|null $errorCode
     */
    public function __construct(string $message, $errorCode = null, ?stdClass $data = null, int $code = 0, ?\Throwable $previous = null)
    {
        $this->errorCode = $errorCode;
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
    /**
     * @return int|string|null
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    public function getData() : ?stdClass
    {
        return $this->data;
    }
}
