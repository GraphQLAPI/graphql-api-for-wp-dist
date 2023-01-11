<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Engine;

use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\Root\Feedback\FeedbackItemResolution;
interface EngineInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getOutputData() : array;
    /**
     * @param string[] $targets
     * @param string $url
     */
    public function addBackgroundUrl($url, $targets) : void;
    public function getEntryComponent() : Component;
    /**
     * @return string[]
     */
    public function getExtraRoutes() : array;
    /**
     * @return mixed[]
     */
    public function listExtraRouteVars() : array;
    /** Must call before `generateDataAndPrepareResponse` */
    public function initializeState() : void;
    public function generateDataAndPrepareResponse() : void;
    public function calculateOutputData() : void;
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelPropsComponentTree($component) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function addRequestPropsComponentTree($component, $props) : array;
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $model_props
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentDatasetSettings($component, $model_props, &$props) : array;
    /**
     * @return array<string,mixed>
     */
    public function getRequestMeta() : array;
    /**
     * @return array<string,mixed>
     */
    public function getSessionMeta() : array;
    /**
     * @return array<string,mixed>
     */
    public function getSiteMeta() : array;
    /**
     * @param CheckpointInterface[] $checkpoints
     */
    public function validateCheckpoints($checkpoints) : ?FeedbackItemResolution;
    /**
     * @return mixed[]
     * @param array<string,mixed> $root_model_props
     * @param array<string,mixed> $root_props
     * @param \PoP\ComponentModel\Component\Component $root_component
     */
    public function getComponentData($root_component, $root_model_props, $root_props) : array;
}
