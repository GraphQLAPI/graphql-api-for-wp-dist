<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
interface ComponentProcessorInterface
{
    /**
     * @return string[]
     */
    public function getComponentNamesToProcess() : array;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getSubcomponents($component) : array;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getAllSubcomponents($component) : array;
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $wildcard_props_to_propagate
     * @param array<string,mixed> $targetted_props_to_propagate
     * @param callable $eval_self_fn
     * @param callable $get_props_for_descendant_components_fn
     * @param callable $get_props_for_descendant_datasetcomponents_fn
     * @param string $propagate_fn
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function executeInitPropsComponentTree($eval_self_fn, $get_props_for_descendant_components_fn, $get_props_for_descendant_datasetcomponents_fn, $propagate_fn, $component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void;
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $wildcard_props_to_propagate
     * @param array<string,mixed> $targetted_props_to_propagate
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initModelPropsComponentTree($component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelPropsForDescendantComponents($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelPropsForDescendantDatasetComponents($component, &$props) : array;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initModelProps($component, &$props) : void;
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $wildcard_props_to_propagate
     * @param array<string,mixed> $targetted_props_to_propagate
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initRequestPropsComponentTree($component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRequestPropsForDescendantComponents($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRequestPropsForDescendantDatasetComponents($component, &$props) : array;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initRequestProps($component, &$props) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function setProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function appendGroupProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function appendProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function mergeGroupProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function mergeProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @return mixed
     * @param string $group
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $property
     */
    public function getGroupProp($group, $component, &$props, $property, $starting_from_componentPath = array());
    /**
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $property
     */
    public function getProp($component, &$props, $property, $starting_from_componentPath = array());
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function mergeGroupIterateKeyProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function mergeIterateKeyProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function pushProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableSettingsDatasetcomponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDatasetsettings($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetOutputKeys($component, &$props) : array;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasource($component, &$props) : string;
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties);
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentMutationResolverBridge($component) : ?ComponentMutationResolverBridgeInterface;
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function prepareDataPropertiesAfterMutationExecution($component, &$props, &$data_properties) : void;
    /**
     * @return LeafComponentFieldNode[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getLeafComponentFieldNodes($component, &$props) : array;
    /**
     * @return RelationalComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalComponentFieldNodes($component) : array;
    /**
     * @return ConditionalLeafComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getConditionalLeafComponentFieldNodes($component) : array;
    /**
     * @return ConditionalRelationalComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getConditionalRelationalComponentFieldNodes($component) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDataPropertiesDatasetcomponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetComponentTreeSectionFlattenedDataProperties($component, &$props) : array;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetcomponentTreeSectionFlattenedComponents($component) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableHeaddatasetcomponentDataProperties($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelDataPropertiesDatasetcomponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelHeaddatasetcomponentDataProperties($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestDataPropertiesDatasetcomponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestHeaddatasetcomponentDataProperties($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getDataFeedbackDatasetcomponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getDataFeedbackComponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getDataFeedback($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array;
    /**
     * @return array<string,mixed>|null
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataFeedbackInterreferencedComponentPath($component, &$props) : ?array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getBackgroundurlsMergeddatasetcomponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getBackgroundurls($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array;
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
    public function getDatasetmeta($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs) : array;
    /**
     * @return CheckpointInterface[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataAccessCheckpoints($component, &$props) : array;
    /**
     * @return CheckpointInterface[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getActionExecutionCheckpoints($component, &$props) : array;
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function shouldExecuteMutation($component, &$props) : bool;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentsToPropagateDataProperties($component) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelSupplementaryDBObjectDataComponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelSupplementaryDBObjectData($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestSupplementaryDBObjectDataComponentTree($component, &$props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestSupplementaryDbobjectdata($component, &$props) : array;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function doesComponentLoadData($component) : bool;
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function startDataloadingSection($component) : bool;
    /**
     * @param FieldInterface[] $pathFields
     * @param array<string,mixed> $props
     * @param array<string,mixed> $ret
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function addToDatasetOutputKeys($component, &$props, $pathFields, &$ret) : void;
    /**
     * @param array<string,mixed> $ret
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function addDatasetcomponentTreeSectionFlattenedComponents(&$ret, $component) : void;
}
