<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\Root\Feedback\FeedbackItemResolution;
abstract class AbstractQueryDataComponentProcessor extends \PoP\ComponentModel\ComponentProcessors\AbstractFilterDataComponentProcessor implements \PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorInterface
{
    use \PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorTrait;
    /**
     * @var \PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler|null
     */
    private $actionExecutionQueryInputOutputHandler;
    /**
     * @param \PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler
     */
    public final function setActionExecutionQueryInputOutputHandler($actionExecutionQueryInputOutputHandler) : void
    {
        $this->actionExecutionQueryInputOutputHandler = $actionExecutionQueryInputOutputHandler;
    }
    protected final function getActionExecutionQueryInputOutputHandler() : ActionExecutionQueryInputOutputHandler
    {
        /** @var ActionExecutionQueryInputOutputHandler */
        return $this->actionExecutionQueryInputOutputHandler = $this->actionExecutionQueryInputOutputHandler ?? $this->instanceManager->getInstance(ActionExecutionQueryInputOutputHandler::class);
    }
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getDatasetmeta($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array
    {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);
        $ret = $this->addQueryHandlerDatasetmeta($ret, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);
        return $ret;
    }
}
