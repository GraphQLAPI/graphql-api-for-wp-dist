<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentPath;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Facades\ComponentPath\ComponentPathHelpersFacade;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\ComponentModel\Tokens\ComponentPath;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;
class ComponentPathHelpers implements \PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface|null
     */
    private $componentPathManager;
    /**
     * @var \PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface|null
     */
    private $componentHelpers;
    /**
     * @param \PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface $componentPathManager
     */
    public final function setComponentPathManager($componentPathManager) : void
    {
        $this->componentPathManager = $componentPathManager;
    }
    protected final function getComponentPathManager() : \PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface
    {
        /** @var ComponentPathManagerInterface */
        return $this->componentPathManager = $this->componentPathManager ?? $this->instanceManager->getInstance(\PoP\ComponentModel\ComponentPath\ComponentPathManagerInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface $componentHelpers
     */
    public final function setComponentHelpers($componentHelpers) : void
    {
        $this->componentHelpers = $componentHelpers;
    }
    protected final function getComponentHelpers() : ComponentHelpersInterface
    {
        /** @var ComponentHelpersInterface */
        return $this->componentHelpers = $this->componentHelpers ?? $this->instanceManager->getInstance(ComponentHelpersInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getStringifiedModulePropagationCurrentPath($component) : string
    {
        $module_propagation_current_path = $this->getComponentPathManager()->getPropagationCurrentPath();
        $module_propagation_current_path[] = $component;
        return $this->stringifyComponentPath($module_propagation_current_path);
    }
    /**
     * @param Component[] $componentPath
     */
    public function stringifyComponentPath($componentPath) : string
    {
        return \implode(ComponentPath::COMPONENT_SEPARATOR, \array_map(\Closure::fromCallable([$this->getComponentHelpers(), 'getComponentOutputName']), $componentPath));
    }
    /**
     * @return array<Component|null>
     * @param string $componentPath_as_string
     */
    public function recastComponentPath($componentPath_as_string) : array
    {
        return \array_map(\Closure::fromCallable([$this->getComponentHelpers(), 'getComponentFromOutputName']), \explode(ComponentPath::COMPONENT_SEPARATOR, $componentPath_as_string));
    }
    /**
     * @return array<array<Component|null>>
     */
    public function getComponentPaths() : array
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return [];
        }
        $paths = Request::getComponentPaths();
        if (!$paths) {
            return [];
        }
        // If any path is a substring from another one, then it is its root, and only this one will be taken into account, so remove its substrings
        // Eg: toplevel.pagesection-top is substring of toplevel, so if passing these 2 componentPaths, keep only toplevel
        // Check that the last character is ".", to avoid toplevel1 to be removed
        $paths = \array_filter($paths, function (string $item) use($paths) : bool {
            foreach ($paths as $path) {
                if (\strlen($item) > \strlen($path) && \strncmp($item, $path, \strlen($path)) === 0 && $item[\strlen($path)] == ComponentPath::COMPONENT_SEPARATOR) {
                    return \false;
                }
            }
            return \true;
        });
        $componentPaths = [];
        foreach ($paths as $path) {
            // Each path must be converted to an array of the modules
            $componentPaths[] = ComponentPathHelpersFacade::getInstance()->recastComponentPath($path);
        }
        return $componentPaths;
    }
}
