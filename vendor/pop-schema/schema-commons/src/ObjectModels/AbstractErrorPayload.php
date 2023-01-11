<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectModels;

use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
abstract class AbstractErrorPayload extends AbstractTransientObject implements \PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface
{
    /**
     * @readonly
     * @var string
     */
    public $message;
    public function __construct(string $message)
    {
        $this->message = $message;
        parent::__construct();
    }
    public function getMessage() : string
    {
        return $this->message;
    }
}
