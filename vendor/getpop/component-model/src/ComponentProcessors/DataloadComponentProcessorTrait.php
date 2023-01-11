<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Constants\HookNames;
use PoP\Root\App;
trait DataloadComponentProcessorTrait
{
    use \PoP\ComponentModel\ComponentProcessors\FormattableModuleTrait;
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getSubcomponents($component) : array
    {
        $ret = parent::getSubcomponents($component);
        if ($filter_component = $this->getFilterSubcomponent($component)) {
            $ret[] = $filter_component;
        }
        if ($inners = $this->getInnerSubcomponents($component)) {
            $ret = \array_merge($ret, $inners);
        }
        return $ret;
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getInnerSubcomponents($component) : array
    {
        return array();
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getFilterSubcomponent($component) : ?Component
    {
        return null;
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function metaInitProps($component, &$props) : void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(HookNames::DATALOAD_INIT_MODEL_PROPS, array(&$props), $component, $this);
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initModelProps($component, &$props) : void
    {
        $this->metaInitProps($component, $props);
        parent::initModelProps($component, $props);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function startDataloadingSection($component) : bool
    {
        return \true;
    }
}
