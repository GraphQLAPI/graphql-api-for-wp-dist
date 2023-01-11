<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectModels;

use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoP\ComponentModel\ObjectModels\AbstractTransientObject;
abstract class AbstractTransientOperationPayload extends AbstractTransientObject
{
    /**
     * @readonly
     * @var string
     */
    public $status;
    /**
     * @var ErrorPayloadInterface[]|null
     * @readonly
     */
    public $errors;
    /**
     * @param ErrorPayloadInterface[]|null $errors
     */
    public function __construct(string $status, ?array $errors)
    {
        $this->status = $status;
        $this->errors = $errors;
        parent::__construct();
    }
}
