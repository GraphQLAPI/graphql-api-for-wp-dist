<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectModels;

use stdClass;
final class GenericErrorPayload extends \PoPSchema\SchemaCommons\ObjectModels\AbstractErrorPayload implements \PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayloadInterface
{
    /**
     * @readonly
     * @var string|null
     */
    public $code;
    /**
     * @readonly
     * @var \stdClass|null
     */
    public $data;
    public function __construct(string $message, ?string $code = null, ?stdClass $data = null)
    {
        $this->code = $code;
        $this->data = $data;
        parent::__construct($message);
    }
    public function getCode() : ?string
    {
        return $this->code;
    }
    public function getData() : ?stdClass
    {
        return $this->data;
    }
}
