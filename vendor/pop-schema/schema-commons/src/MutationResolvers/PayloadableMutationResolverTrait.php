<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ObjectMutationPayload;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
trait PayloadableMutationResolverTrait
{
    /**
     * Override: Do nothing, because the app-level errors are
     * returned in the Payload, not in top-level "errors" entry.
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore) : void
    {
    }
    /**
     * @param string|int $objectID
     */
    protected function createSuccessObjectMutationPayload($objectID) : ObjectMutationPayload
    {
        return new ObjectMutationPayload(OperationStatusEnum::SUCCESS, $objectID, null);
    }
    /**
     * @param ErrorPayloadInterface[] $errors
     * @param string|int|null $objectID
     */
    protected function createFailureObjectMutationPayload($errors, $objectID = null) : ObjectMutationPayload
    {
        return new ObjectMutationPayload(OperationStatusEnum::FAILURE, $objectID, $errors);
    }
    /**
     * @param \PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException $payloadClientException
     */
    protected function createGenericErrorPayloadFromPayloadClientException($payloadClientException) : GenericErrorPayload
    {
        $errorCode = $payloadClientException->getErrorCode();
        if ($errorCode !== null) {
            $errorCode = (string) $payloadClientException->getErrorCode();
        }
        return new GenericErrorPayload($payloadClientException->getMessage(), $errorCode, $payloadClientException->getData());
    }
}
