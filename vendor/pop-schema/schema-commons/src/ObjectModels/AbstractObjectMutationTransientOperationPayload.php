<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectModels;

use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use RuntimeException;
abstract class AbstractObjectMutationTransientOperationPayload extends \PoPSchema\SchemaCommons\ObjectModels\AbstractTransientOperationPayload
{
    /**
     * @readonly
     * @var string|int|null
     */
    public $objectID;
    /**
     * Either the object ID or the error must be provided.
     * If both of them are null, it's a development error.
     *
     * @param ErrorPayloadInterface[]|null $errors
     * @param string|int|null $objectID
     */
    public function __construct(string $status, $objectID, ?array $errors)
    {
        $this->objectID = $objectID;
        if ($objectID === null && ($errors === null || $errors === [])) {
            throw new RuntimeException($this->__('Either the object ID or the error(s) must be provided', 'schema-commons'));
        }
        parent::__construct($status, $errors);
    }
}
