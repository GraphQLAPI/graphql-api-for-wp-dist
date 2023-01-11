<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentHelpers;

use PoP\ComponentModel\Component\Component;
use PoP\Definitions\DefinitionManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
class ComponentHelpers implements \PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface
{
    use BasicServiceTrait;
    public const SEPARATOR_PROCESSORCOMPONENTFULLNAME = "::";
    /**
     * @var \PoP\Definitions\DefinitionManagerInterface|null
     */
    private $definitionManager;
    /**
     * @param \PoP\Definitions\DefinitionManagerInterface $definitionManager
     */
    public final function setDefinitionManager($definitionManager) : void
    {
        $this->definitionManager = $definitionManager;
    }
    protected final function getDefinitionManager() : DefinitionManagerInterface
    {
        /** @var DefinitionManagerInterface */
        return $this->definitionManager = $this->definitionManager ?? $this->instanceManager->getInstance(DefinitionManagerInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentFullName($component) : string
    {
        $componentFullName = $component->processorClass . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $component->name;
        if ($component->atts !== []) {
            $componentFullName .= self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . \serialize($component->atts);
        }
        return $componentFullName;
    }
    /**
     * @param string $componentFullName
     */
    public function getComponentFromFullName($componentFullName) : ?Component
    {
        $parts = \explode(self::SEPARATOR_PROCESSORCOMPONENTFULLNAME, $componentFullName);
        // There must be at least 2 parts: class and name
        if (\count($parts) < 2) {
            return null;
        }
        $processorClass = (string) $parts[0];
        $name = (string) $parts[1];
        $atts = [];
        // Does it have componentAtts? If so, unserialize them.
        if (isset($parts[2])) {
            $atts = (array) \unserialize(
                // Just in case componentAtts contains the same SEPARATOR_PROCESSORCOMPONENTFULLNAME string inside of it, simply recalculate the whole componentAtts from the $componentFullName string
                \substr($componentFullName, \strlen($processorClass . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME . $name . self::SEPARATOR_PROCESSORCOMPONENTFULLNAME))
            );
        }
        return new Component($processorClass, $name, $atts);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentOutputName($component) : string
    {
        return $this->getDefinitionManager()->getDefinition($this->getComponentFullName($component), \PoP\ComponentModel\ComponentHelpers\DefinitionGroups::COMPONENTS);
    }
    /**
     * @param string $componentOutputName
     */
    public function getComponentFromOutputName($componentOutputName) : ?Component
    {
        return $this->getComponentFromFullName($this->getDefinitionManager()->getOriginalName($componentOutputName, \PoP\ComponentModel\ComponentHelpers\DefinitionGroups::COMPONENTS));
    }
}
