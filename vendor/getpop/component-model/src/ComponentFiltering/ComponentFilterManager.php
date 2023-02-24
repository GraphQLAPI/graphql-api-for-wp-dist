<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentFiltering;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFilters\ComponentFilterInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface;
use PoP\ComponentModel\Configuration\Request;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;
class ComponentFilterManager implements \PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface
{
    use BasicServiceTrait;
    /**
     * @var string|null
     */
    protected $selected_filter_name;
    /**
     * @var \PoP\ComponentModel\ComponentFilters\ComponentFilterInterface|null
     */
    private $selected_filter;
    /**
     * @var array<string,ComponentFilterInterface>
     */
    protected $componentfilters = [];
    /**
     * @var bool
     */
    protected $initialized = \false;
    /**
     * From the moment in which a component is not excluded,
     * every component from then on must also be included
     * @var string|null
     */
    protected $not_excluded_ancestor_component;
    /**
     * @var array<mixed[]>|null
     */
    protected $not_excluded_component_sets;
    /**
     * @var string[]|null
     */
    protected $not_excluded_component_sets_as_string;
    /**
     * When targeting components in pop-engine.php (eg: when doing ->getObjectIDs())
     * those components are already and always included, so no need to check
     * for their ancestors or anything
     * @var bool
     */
    protected $neverExclude = \false;
    /**
     * @var \PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface|null
     */
    private $componentPathManager;
    /**
     * @var \PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface|null
     */
    private $componentPathHelpers;
    /**
     * @param \PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface $componentPathManager
     */
    public final function setComponentPathManager($componentPathManager) : void
    {
        $this->componentPathManager = $componentPathManager;
    }
    protected final function getComponentPathManager() : ComponentPathManagerInterface
    {
        /** @var ComponentPathManagerInterface */
        return $this->componentPathManager = $this->componentPathManager ?? $this->instanceManager->getInstance(ComponentPathManagerInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface $componentPathHelpers
     */
    public final function setComponentPathHelpers($componentPathHelpers) : void
    {
        $this->componentPathHelpers = $componentPathHelpers;
    }
    protected final function getComponentPathHelpers() : ComponentPathHelpersInterface
    {
        /** @var ComponentPathHelpersInterface */
        return $this->componentPathHelpers = $this->componentPathHelpers ?? $this->instanceManager->getInstance(ComponentPathHelpersInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\ComponentFilters\ComponentFilterInterface $componentFilter
     */
    public function addComponentFilter($componentFilter) : void
    {
        $this->componentfilters[$componentFilter->getName()] = $componentFilter;
    }
    protected function init() : void
    {
        // Lazy initialize so that we can inject all the componentFilters before checking the selected one
        $this->selected_filter_name = $this->selected_filter_name ?? $this->getSelectedComponentFilterName();
        if ($this->selected_filter_name) {
            $this->selected_filter = $this->componentfilters[$this->selected_filter_name] ?? null;
            // Initialize only if we are intending to filter components. This way, passing componentFilter=somewrongpath will return an empty array, meaning to not render anything
            $this->not_excluded_component_sets = $this->not_excluded_component_sets_as_string = array();
        }
        $this->initialized = \true;
    }
    /**
     * The selected filter can be set from outside by the engine
     * @param string $selectedComponentFilterName
     */
    public function setSelectedComponentFilterName($selectedComponentFilterName) : void
    {
        $this->selected_filter_name = $selectedComponentFilterName;
    }
    public function getSelectedComponentFilterName() : ?string
    {
        if ($this->selected_filter_name) {
            return $this->selected_filter_name;
        }
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return null;
        }
        // Only valid if there's a corresponding componentFilter
        $selectedComponentFilterName = Request::getComponentFilter();
        if ($selectedComponentFilterName !== null && \in_array($selectedComponentFilterName, \array_keys($this->componentfilters))) {
            return $selectedComponentFilterName;
        }
        return null;
    }
    /**
     * @return array<mixed[]>|null
     */
    public function getNotExcludedComponentSets() : ?array
    {
        // It shall be used for requestmeta.rendercomponents, to know from which components the client must start rendering
        return $this->not_excluded_component_sets;
    }
    /**
     * @param bool $neverExclude
     */
    public function setNeverExclude($neverExclude) : void
    {
        $this->neverExclude = $neverExclude;
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function excludeSubcomponent($component, &$props) : bool
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if ($this->neverExclude) {
                return \false;
            }
            if ($this->not_excluded_ancestor_component !== null) {
                return \false;
            }
            return $this->selected_filter->excludeSubcomponent($component, $props);
        }
        return \false;
    }
    /**
     * @param Component[] $subcomponents
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function removeExcludedSubcomponents($component, $subcomponents) : array
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if ($this->neverExclude) {
                return $subcomponents;
            }
            return $this->selected_filter->removeExcludedSubcomponents($component, $subcomponents);
        }
        return $subcomponents;
    }
    /**
     * The `prepare` function advances the componentPath one level down, when interating into the subcomponents, and then calling `restore` the value goes one level up again
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function prepareForPropagation($component, &$props) : void
    {
        if (!$this->initialized) {
            $this->init();
        }
        if ($this->selected_filter !== null) {
            if (!$this->neverExclude && $this->not_excluded_ancestor_component === null && $this->excludeSubcomponent($component, $props) === \false) {
                // Set the current component as the one which is not excluded.
                $component_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
                $component_propagation_current_path[] = $component;
                $this->not_excluded_ancestor_component = $this->getComponentPathHelpers()->stringifyComponentPath($component_propagation_current_path);
                // Add it to the list of not-excluded components
                /** @var string[] */
                $not_excluded_component_sets_as_string = $this->not_excluded_component_sets_as_string;
                if (!\in_array($this->not_excluded_ancestor_component, $not_excluded_component_sets_as_string)) {
                    $this->not_excluded_component_sets_as_string[] = $this->not_excluded_ancestor_component;
                    $this->not_excluded_component_sets[] = $component_propagation_current_path;
                }
            }
            $this->selected_filter->prepareForPropagation($component, $props);
        }
        // Add the component to the path
        $this->getComponentPathManager()->prepareForPropagation($component, $props);
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function restoreFromPropagation($component, &$props) : void
    {
        if (!$this->initialized) {
            $this->init();
        }
        // Remove the component from the path
        $this->getComponentPathManager()->restoreFromPropagation($component, $props);
        if ($this->selected_filter !== null) {
            if (!$this->neverExclude && $this->not_excluded_ancestor_component !== null && $this->excludeSubcomponent($component, $props) === \false) {
                $component_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
                $component_propagation_current_path[] = $component;
                // If the current component was set as the one not excluded, then reset it
                if ($this->not_excluded_ancestor_component === $this->getComponentPathHelpers()->stringifyComponentPath($component_propagation_current_path)) {
                    $this->not_excluded_ancestor_component = null;
                }
            }
            $this->selected_filter->restoreFromPropagation($component, $props);
        }
    }
}
