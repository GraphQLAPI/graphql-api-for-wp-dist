<?php

declare (strict_types=1);
namespace PoP\ComponentModel\QueryInputOutputHandlers;

use PoP\Root\Feedback\FeedbackItemResolution;
interface QueryInputOutputHandlerInterface
{
    /**
     * @param array<string,mixed> $query_args
     */
    public function prepareQueryArgs(&$query_args) : void;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getQueryState($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getQueryParams($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getQueryResult($data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array;
}
