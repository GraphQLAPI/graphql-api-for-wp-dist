<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\ComponentFilters\ComponentPaths;
use PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface;
use PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface;
use PoP\ComponentModel\Constants\Constants;
use PoP\ComponentModel\Constants\DataLoading;
use PoP\ComponentModel\Constants\DataProperties;
use PoP\ComponentModel\Constants\DataSources;
use PoP\ComponentModel\Constants\FieldOutputKeys;
use PoP\ComponentModel\Constants\Props;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ComponentFieldNodeInterface;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalLeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\ConditionalRelationalComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentFieldNode;
use PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;
abstract class AbstractComponentProcessor implements \PoP\ComponentModel\ComponentProcessors\ComponentProcessorInterface
{
    use \PoP\ComponentModel\ComponentProcessors\ComponentPathProcessorTrait;
    use BasicServiceTrait;
    public const HOOK_INIT_MODEL_PROPS = __CLASS__ . ':initModelProps';
    public const HOOK_INIT_REQUEST_PROPS = __CLASS__ . ':initRequestProps';
    public const HOOK_ADD_HEADDATASETCOMPONENT_DATAPROPERTIES = __CLASS__ . ':addHeaddatasetcomponentDataProperties';
    protected const COMPONENTELEMENT_SUBCOMPONENTS = 'subcomponents';
    protected const COMPONENTELEMENT_RELATIONALSUBCOMPONENTS = 'relational-subcomponents';
    protected const COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS = 'conditional-on-data-field-subcomponents';
    protected const COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS = 'conditional-on-data-field-relational-subcomponents';
    /**
     * @var \PoP\ComponentModel\ComponentPath\ComponentPathHelpersInterface|null
     */
    private $componentPathHelpers;
    /**
     * @var \PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface|null
     */
    private $componentFilterManager;
    /**
     * @var \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface|null
     */
    private $componentProcessorManager;
    /**
     * @var \PoP\LooseContracts\NameResolverInterface|null
     */
    private $nameResolver;
    /**
     * @var \PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface|null
     */
    private $dataloadHelperService;
    /**
     * @var \PoP\ComponentModel\HelperServices\RequestHelperServiceInterface|null
     */
    private $requestHelperService;
    /**
     * @var \PoP\ComponentModel\ComponentFilters\ComponentPaths|null
     */
    private $componentPaths;
    /**
     * @var \PoP\ComponentModel\ComponentHelpers\ComponentHelpersInterface|null
     */
    private $componentHelpers;
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
     * @param \PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface $componentFilterManager
     */
    public final function setComponentFilterManager($componentFilterManager) : void
    {
        $this->componentFilterManager = $componentFilterManager;
    }
    protected final function getComponentFilterManager() : ComponentFilterManagerInterface
    {
        /** @var ComponentFilterManagerInterface */
        return $this->componentFilterManager = $this->componentFilterManager ?? $this->instanceManager->getInstance(ComponentFilterManagerInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface $componentProcessorManager
     */
    public final function setComponentProcessorManager($componentProcessorManager) : void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    protected final function getComponentProcessorManager() : \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface
    {
        /** @var ComponentProcessorManagerInterface */
        return $this->componentProcessorManager = $this->componentProcessorManager ?? $this->instanceManager->getInstance(\PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface::class);
    }
    /**
     * @param \PoP\LooseContracts\NameResolverInterface $nameResolver
     */
    public final function setNameResolver($nameResolver) : void
    {
        $this->nameResolver = $nameResolver;
    }
    protected final function getNameResolver() : NameResolverInterface
    {
        /** @var NameResolverInterface */
        return $this->nameResolver = $this->nameResolver ?? $this->instanceManager->getInstance(NameResolverInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface $dataloadHelperService
     */
    public final function setDataloadHelperService($dataloadHelperService) : void
    {
        $this->dataloadHelperService = $dataloadHelperService;
    }
    protected final function getDataloadHelperService() : DataloadHelperServiceInterface
    {
        /** @var DataloadHelperServiceInterface */
        return $this->dataloadHelperService = $this->dataloadHelperService ?? $this->instanceManager->getInstance(DataloadHelperServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\HelperServices\RequestHelperServiceInterface $requestHelperService
     */
    public final function setRequestHelperService($requestHelperService) : void
    {
        $this->requestHelperService = $requestHelperService;
    }
    protected final function getRequestHelperService() : RequestHelperServiceInterface
    {
        /** @var RequestHelperServiceInterface */
        return $this->requestHelperService = $this->requestHelperService ?? $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }
    /**
     * @param \PoP\ComponentModel\ComponentFilters\ComponentPaths $componentPaths
     */
    public final function setComponentPaths($componentPaths) : void
    {
        $this->componentPaths = $componentPaths;
    }
    protected final function getComponentPaths() : ComponentPaths
    {
        /** @var ComponentPaths */
        return $this->componentPaths = $this->componentPaths ?? $this->instanceManager->getInstance(ComponentPaths::class);
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
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getSubcomponents($component) : array
    {
        return [];
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public final function getAllSubcomponents($component) : array
    {
        return $this->getSubcomponentsByGroup($component);
    }
    // public function getNature(\PoP\ComponentModel\Component\Component $component)
    // {
    //     return null;
    // }
    //-------------------------------------------------
    // New PUBLIC Functions: Atts
    //-------------------------------------------------
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
    public function executeInitPropsComponentTree($eval_self_fn, $get_props_for_descendant_components_fn, $get_props_for_descendant_datasetcomponents_fn, $propagate_fn, $component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void
    {
        // Convert the component to its string representation to access it in the array
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        // Initialize. If this component had been added props, then use them already
        // 1st element to merge: the general props for this component passed down the line
        // 2nd element to merge: the props set exactly to the path. They have more priority, that's why they are 2nd
        // It may contain more than one group (\PoP\ComponentModel\Constants\Props::ATTRIBUTES). Eg: maybe also POP_PROPS_JSMETHODS
        $props[$componentFullName] = \array_merge_recursive($targetted_props_to_propagate[$componentFullName] ?? array(), $props[$componentFullName] ?? array());
        // The component must be at the head of the $props array passed to all `initModelProps`, so that function `getPathHeadComponent` can work
        $component_props = array($componentFullName => &$props[$componentFullName]);
        // If ancestor components set general props, or props targetted at this current component, then add them to the current component props
        foreach ($wildcard_props_to_propagate as $key => $value) {
            $this->setProp($component, $component_props, $key, $value);
        }
        // Before initiating the current level, set the children attributes on the array, so that doing ->setProp, ->appendProp, etc, keeps working
        $component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] = \array_merge($component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] ?? array(), $targetted_props_to_propagate);
        // Initiate the current level.
        $eval_self_fn($component, $component_props);
        // Immediately after initiating the current level, extract all child attributes out from the $props, and place it on the other variable
        $targetted_props_to_propagate = $component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES];
        unset($component_props[$componentFullName][Props::DESCENDANT_ATTRIBUTES]);
        // But because components can't repeat themselves down the line (or it would generate an infinite loop), then can remove the current component from the targeted props
        unset($targetted_props_to_propagate[$componentFullName]);
        // Allow the $component to add general props for all its descendant components
        $wildcard_props_to_propagate = \array_merge($wildcard_props_to_propagate, $get_props_for_descendant_components_fn($component, $component_props));
        // Propagate
        $subcomponents = $this->getAllSubcomponents($component);
        $subcomponents = $this->getComponentFilterManager()->removeExcludedSubcomponents($component, $subcomponents);
        // This function must be called always, to register matching components into requestmeta.filtercomponents even when the component has no subcomponents
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        if ($subcomponents) {
            $props[$componentFullName][Props::SUBCOMPONENTS] = $props[$componentFullName][Props::SUBCOMPONENTS] ?? array();
            foreach ($subcomponents as $subcomponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
                $subcomponent_wildcard_props_to_propagate = $wildcard_props_to_propagate;
                // If the subcomponent belongs to the same dataset, then set the shared attributies for the same-dataset components
                if (!$subcomponent_processor->startDataloadingSection($subcomponent)) {
                    $subcomponent_wildcard_props_to_propagate = \array_merge($subcomponent_wildcard_props_to_propagate, $get_props_for_descendant_datasetcomponents_fn($component, $component_props));
                }
                $subcomponent_processor->{$propagate_fn}($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS], $subcomponent_wildcard_props_to_propagate, $targetted_props_to_propagate);
            }
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $wildcard_props_to_propagate
     * @param array<string,mixed> $targetted_props_to_propagate
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initModelPropsComponentTree($component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void
    {
        $this->executeInitPropsComponentTree(\Closure::fromCallable([$this, 'initModelProps']), \Closure::fromCallable([$this, 'getModelPropsForDescendantComponents']), \Closure::fromCallable([$this, 'getModelPropsForDescendantDatasetComponents']), __FUNCTION__, $component, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelPropsForDescendantComponents($component, &$props) : array
    {
        $ret = array();
        // If we set property 'skip-data-load' on any component, not just dataset, spread it down to its children so it reaches its contained dataset subcomponents
        $skip_data_load = $this->getProp($component, $props, 'skip-data-load');
        if (!\is_null($skip_data_load)) {
            $ret['skip-data-load'] = $skip_data_load;
        }
        // Property 'ignore-request-params' => true makes a dataloading component not get values from the request
        $ignore_params_from_request = $this->getProp($component, $props, 'ignore-request-params');
        if (!\is_null($ignore_params_from_request)) {
            $ret['ignore-request-params'] = $ignore_params_from_request;
        }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelPropsForDescendantDatasetComponents($component, &$props) : array
    {
        return [];
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initModelProps($component, &$props) : void
    {
        // Set property "succeeding-typeResolver" on every component, so they know which is their typeResolver, needed to calculate the subcomponent data-fields when using typeResolver "*"
        if ($relationalTypeResolver = $this->getRelationalTypeResolver($component)) {
            $this->setProp($component, $props, 'succeeding-typeResolver', $relationalTypeResolver);
        } else {
            // Get the prop assigned to the component by its ancestor
            $relationalTypeResolver = $this->getProp($component, $props, 'succeeding-typeResolver');
        }
        if ($relationalTypeResolver !== null) {
            // Set the property "succeeding-typeResolver" on all descendants: the same typeResolver for all subcomponents, and the explicit one (or get the default one for "*") for relational objects
            foreach ($this->getSubcomponents($component) as $subcomponent) {
                $this->setProp($subcomponent, $props, 'succeeding-typeResolver', $relationalTypeResolver);
            }
            foreach ($this->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
                /**
                 * Also add errors to the feedback (eg: `{ id { id } }`)
                 */
                $subcomponent_typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($relationalTypeResolver, $relationalComponentFieldNode->getField());
                if (!$subcomponent_typeResolver) {
                    continue;
                }
                foreach ($relationalComponentFieldNode->getNestedComponents() as $subcomponent_component) {
                    $this->setProp($subcomponent_component, $props, 'succeeding-typeResolver', $subcomponent_typeResolver);
                }
            }
            foreach ($this->getConditionalLeafComponentFieldNodes($component) as $conditionalLeafComponentFieldNode) {
                foreach ($conditionalLeafComponentFieldNode->getConditionalNestedComponents() as $conditionalSubcomponent) {
                    $this->setProp($conditionalSubcomponent, $props, 'succeeding-typeResolver', $relationalTypeResolver);
                }
            }
            foreach ($this->getConditionalRelationalComponentFieldNodes($component) as $conditionalRelationalComponentFieldNode) {
                foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $relationalComponentFieldNode) {
                    /**
                     * Also add errors to the feedback (eg: `{ id { id } }`)
                     */
                    $subcomponentTypeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($relationalTypeResolver, $relationalComponentFieldNode->getField());
                    if (!$subcomponentTypeResolver) {
                        continue;
                    }
                    foreach ($relationalComponentFieldNode->getNestedComponents() as $conditionalSubcomponent) {
                        $this->setProp($conditionalSubcomponent, $props, 'succeeding-typeResolver', $subcomponentTypeResolver);
                    }
                }
            }
        }
        /**
         * Allow to add more stuff
         */
        App::doAction(self::HOOK_INIT_MODEL_PROPS, array(&$props), $component, $this);
    }
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $wildcard_props_to_propagate
     * @param array<string,mixed> $targetted_props_to_propagate
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initRequestPropsComponentTree($component, &$props, $wildcard_props_to_propagate, $targetted_props_to_propagate) : void
    {
        $this->executeInitPropsComponentTree(\Closure::fromCallable([$this, 'initRequestProps']), \Closure::fromCallable([$this, 'getRequestPropsForDescendantComponents']), \Closure::fromCallable([$this, 'getRequestPropsForDescendantDatasetComponents']), __FUNCTION__, $component, $props, $wildcard_props_to_propagate, $targetted_props_to_propagate);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRequestPropsForDescendantComponents($component, &$props) : array
    {
        return [];
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRequestPropsForDescendantDatasetComponents($component, &$props) : array
    {
        return [];
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function initRequestProps($component, &$props) : void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(self::HOOK_INIT_REQUEST_PROPS, array(&$props), $component, $this);
    }
    //-------------------------------------------------
    // PRIVATE Functions: Atts
    //-------------------------------------------------
    /**
     * @param array<string,mixed> $props
     */
    private function getPathHeadComponent(array &$props) : string
    {
        // From the root of the $props we obtain the current component
        \reset($props);
        return (string) \key($props);
    }
    /**
     * $component_or_componentPath can be either a single component
     * (the current one, or its descendant), or a targetted path
     * of components
     *
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     */
    private function isComponentPath($component_or_componentPath) : bool
    {
        return \is_array($component_or_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     */
    private function isDescendantComponent($component_or_componentPath, array &$props) : bool
    {
        if ($this->isComponentPath($component_or_componentPath)) {
            return \false;
        }
        /** @var Component */
        $component = $component_or_componentPath;
        // From the root of the $props we obtain the current component
        $componentFullName = $this->getPathHeadComponent($props);
        // If the component were we are adding the att, is this same component, then we are already at the path
        // If it is not, then go down one level to that component
        return $componentFullName !== $this->getComponentHelpers()->getComponentFullName($component);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @return string[]
     * @param array<string,mixed> $props
     */
    protected function getComponentPath($component_or_componentPath, &$props) : array
    {
        // This function is used to get the path to the current component, or to a component path
        // It is not used for getting the path to a single component which is not the current one (since we do not know its path)
        if (!$props) {
            return [];
        }
        // From the root of the $props we obtain the current component
        $componentFullName = $this->getPathHeadComponent($props);
        // Calculate the path to iterate down. It always starts with the current component
        $ret = array($componentFullName);
        if (!$this->isComponentPath($component_or_componentPath)) {
            return $ret;
        }
        /** @var mixed[] */
        $componentPath = $component_or_componentPath;
        // We're passing the path to find the component to which to add the att
        return \array_merge($ret, \array_map(\Closure::fromCallable([$this->getComponentHelpers(), 'getComponentFullName']), $componentPath));
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param array<string,mixed> $options
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    protected function addPropGroupField($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array(), $options = array()) : void
    {
        // Iterate down to the subcomponent, which must be an array of components
        if ($starting_from_componentPath) {
            // Convert it to string
            $startingFromComponentPathFullNames = \array_map(\Closure::fromCallable([$this->getComponentHelpers(), 'getComponentFullName']), $starting_from_componentPath);
            // Attach the current component, which is not included on "starting_from", to step down this level too
            $componentFullName = $this->getPathHeadComponent($props);
            \array_unshift($startingFromComponentPathFullNames, $componentFullName);
            // Descend into the path to find the component for which to add the att
            $component_props =& $props;
            foreach ($startingFromComponentPathFullNames as $pathlevelComponentFullName) {
                $last_component_props =& $component_props;
                $lastComponentFullName = $pathlevelComponentFullName;
                $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS] = $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS] ?? array();
                $component_props =& $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS];
            }
            // This is the new $props, so it starts from here
            // Save the current $props, and restore later, to make sure this array has only one key, otherwise it will not work
            $current_props = $props;
            $props = array($lastComponentFullName => &$last_component_props[$lastComponentFullName]);
        }
        // If the component is a string, there are 2 possibilities: either it is the current component or not
        // If it is not, then it is a descendant component, which will appear at some point down the path.
        // For that case, simply save it under some other entry, from where it will propagate the props later on in `initModelPropsComponentTree`
        if ($this->isDescendantComponent($component_or_componentPath, $props)) {
            // It is a child component
            /** @var Component */
            $att_component = $component_or_componentPath;
            $attComponentFullName = $this->getComponentHelpers()->getComponentFullName($att_component);
            // From the root of the $props we obtain the current component
            $componentFullName = $this->getPathHeadComponent($props);
            // Set the child attributes under a different entry
            $props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] = $props[$componentFullName][Props::DESCENDANT_ATTRIBUTES] ?? array();
            $component_props =& $props[$componentFullName][Props::DESCENDANT_ATTRIBUTES];
        } else {
            // Calculate the path to iterate down
            $componentPath = $this->getComponentPath($component_or_componentPath, $props);
            // Extract the lastlevel, that's the component to with to add the att
            $attComponentFullName = \array_pop($componentPath);
            // Descend into the path to find the component for which to add the att
            $component_props =& $props;
            foreach ($componentPath as $pathlevelFullName) {
                $component_props[$pathlevelFullName][Props::SUBCOMPONENTS] = $component_props[$pathlevelFullName][Props::SUBCOMPONENTS] ?? array();
                $component_props =& $component_props[$pathlevelFullName][Props::SUBCOMPONENTS];
            }
        }
        // Now can proceed to add the att
        $component_props[$attComponentFullName][$group] = $component_props[$attComponentFullName][$group] ?? array();
        if ($options['append'] ?? null) {
            $component_props[$attComponentFullName][$group][$property] = $component_props[$attComponentFullName][$group][$property] ?? '';
            $component_props[$attComponentFullName][$group][$property] .= ' ' . $value;
        } elseif ($options['array'] ?? null) {
            $component_props[$attComponentFullName][$group][$property] = $component_props[$attComponentFullName][$group][$property] ?? array();
            if ($options['merge'] ?? null) {
                $component_props[$attComponentFullName][$group][$property] = \array_merge($component_props[$attComponentFullName][$group][$property], $value);
            } elseif ($options['merge-iterate-key'] ?? null) {
                foreach ($value as $value_key => $value_value) {
                    if (!$component_props[$attComponentFullName][$group][$property][$value_key]) {
                        $component_props[$attComponentFullName][$group][$property][$value_key] = array();
                    }
                    // Doing array_unique, because in the NotificationPreviewLayout, different layouts might impose a JS down the road, many times, and these get duplicated
                    $component_props[$attComponentFullName][$group][$property][$value_key] = \array_unique(\array_merge($component_props[$attComponentFullName][$group][$property][$value_key], $value_value));
                }
            } elseif ($options['push'] ?? null) {
                \array_push($component_props[$attComponentFullName][$group][$property], $value);
            }
        } else {
            // If already set, then do nothing
            if (!isset($component_props[$attComponentFullName][$group][$property])) {
                $component_props[$attComponentFullName][$group][$property] = $value;
            }
        }
        // Restore original $props
        if ($starting_from_componentPath) {
            $props = $current_props;
        }
    }
    /**
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @return mixed
     * @param string $group
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $property
     */
    protected function getPropGroupField($group, $component, &$props, $property, $starting_from_componentPath = array())
    {
        $group = $this->getPropGroup($group, $component, $props, $starting_from_componentPath);
        return $group[$property] ?? null;
    }
    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param string $group
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getPropGroup($group, $component, &$props, $starting_from_componentPath = array()) : array
    {
        if (!$props) {
            return [];
        }
        $component_props =& $props;
        foreach ($starting_from_componentPath as $pathlevelComponent) {
            $pathlevelComponentFullName = $this->getComponentHelpers()->getComponentFullName($pathlevelComponent);
            $component_props =& $component_props[$pathlevelComponentFullName][Props::SUBCOMPONENTS];
        }
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        return $component_props[$componentFullName][$group] ?? array();
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    protected function addGroupProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function setProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function appendGroupProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath, array('append' => \true));
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function appendProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->appendGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function mergeGroupProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath, array('array' => \true, 'merge' => \true));
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function mergeProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->mergeGroupProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath);
    }
    /**
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @return mixed
     * @param string $group
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $property
     */
    public function getGroupProp($group, $component, &$props, $property, $starting_from_componentPath = array())
    {
        return $this->getPropGroupField($group, $component, $props, $property, $starting_from_componentPath);
    }
    /**
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @return mixed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param string $property
     */
    public function getProp($component, &$props, $property, $starting_from_componentPath = array())
    {
        return $this->getGroupProp(Props::ATTRIBUTES, $component, $props, $property, $starting_from_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function mergeGroupIterateKeyProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath, array('array' => \true, 'merge-iterate-key' => \true));
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $property
     */
    public function mergeIterateKeyProp($component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->mergeGroupIterateKeyProp(Props::ATTRIBUTES, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath);
    }
    /**
     * @param mixed[]|\PoP\ComponentModel\Component\Component $component_or_componentPath
     * @param array<string,mixed> $props
     * @param Component[] $starting_from_componentPath
     * @param mixed $value
     * @param string $group
     * @param string $property
     */
    public function pushProp($group, $component_or_componentPath, &$props, $property, $value, $starting_from_componentPath = array()) : void
    {
        $this->addPropGroupField($group, $component_or_componentPath, $props, $property, $value, $starting_from_componentPath, array('array' => \true, 'push' => \true));
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Model Static Settings
    //-------------------------------------------------
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableSettingsDatasetcomponentTree($component, &$props) : array
    {
        $options = array('only-execute-on-dataloading-components' => \true);
        return $this->executeOnSelfAndPropagateToComponents('getImmutableDatasetsettings', __FUNCTION__, $component, $props, \true, $options);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDatasetsettings($component, &$props) : array
    {
        $ret = array();
        if ($outputKeys = $this->getDatasetOutputKeys($component, $props)) {
            $ret['outputKeys'] = $outputKeys;
        }
        return $ret;
    }
    /**
     * @param FieldInterface[] $pathFields
     * @param array<string,mixed> $props
     * @param array<string,mixed> $ret
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function addToDatasetOutputKeys($component, &$props, $pathFields, &$ret) : void
    {
        // Add the current component's outputKeys
        $this->addFieldsToDatasetOutputKeys($component, $props, $pathFields, $ret);
        // Propagate to all subcomponents which have no typeResolver
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        if ($this->getProp($component, $props, 'succeeding-typeResolver') !== null) {
            $this->getComponentFilterManager()->prepareForPropagation($component, $props);
            foreach ($this->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
                // Only components which do not load data
                $subcomponent_components = \array_filter($relationalComponentFieldNode->getNestedComponents(), function ($subcomponent) : bool {
                    return !$this->getComponentProcessorManager()->getComponentProcessor($subcomponent)->startDataloadingSection($subcomponent);
                });
                /** @var FieldInterface[] */
                $subcomponentPathFields = \array_merge($pathFields, [$relationalComponentFieldNode->getField()]);
                foreach ($subcomponent_components as $subcomponent_component) {
                    $this->getComponentProcessorManager()->getComponentProcessor($subcomponent_component)->addToDatasetOutputKeys($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS], $subcomponentPathFields, $ret);
                }
            }
            foreach ($this->getConditionalRelationalComponentFieldNodes($component) as $conditionalRelationalComponentFieldNode) {
                foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $relationalComponentFieldNode) {
                    // Only components which do not load data
                    $subcomponent_components = \array_filter($relationalComponentFieldNode->getNestedComponents(), function (Component $subcomponent) {
                        return !$this->getComponentProcessorManager()->getComponentProcessor($subcomponent)->startDataloadingSection($subcomponent);
                    });
                    /** @var FieldInterface[] */
                    $subcomponentPathFields = \array_merge($pathFields, [$relationalComponentFieldNode->getField()]);
                    foreach ($subcomponent_components as $subcomponent_component) {
                        $this->getComponentProcessorManager()->getComponentProcessor($subcomponent_component)->addToDatasetOutputKeys($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS], $subcomponentPathFields, $ret);
                    }
                }
            }
            // Only components which do not load data
            $subcomponents = \array_filter($this->getSubcomponents($component), function (Component $subcomponent) {
                return !$this->getComponentProcessorManager()->getComponentProcessor($subcomponent)->startDataloadingSection($subcomponent);
            });
            foreach ($subcomponents as $subcomponent) {
                $this->getComponentProcessorManager()->getComponentProcessor($subcomponent)->addToDatasetOutputKeys($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS], $pathFields, $ret);
            }
            $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
        }
    }
    /**
     * @param FieldInterface[] $pathFields
     * @param array<string,mixed> $props
     * @param array<string,mixed> $ret
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function addFieldsToDatasetOutputKeys($component, &$props, $pathFields, &$ret) : void
    {
        if ($relationalTypeResolver = $this->getRelationalTypeResolver($component)) {
            /**
             * Place it under "id" because it is for fetching the current object
             * from the DB, which is found through resolvedObject.id
             */
            $field = new LeafField(FieldOutputKeys::ID, null, [], [], ASTNodesFactory::getNonSpecificLocation());
            /** @var FieldInterface[] */
            $selfPathFields = \array_merge($pathFields, [$field]);
            $selfPathFieldOutputKeys = \array_map(function (FieldInterface $field) {
                return $field->getOutputKey();
            }, $selfPathFields);
            $ret[\implode(Constants::RELATIONAL_FIELD_PATH_SEPARATOR, $selfPathFieldOutputKeys)] = $relationalTypeResolver->getTypeOutputKey();
        }
        // This prop is set for both dataloading and non-dataloading components
        if ($relationalTypeResolver = $this->getProp($component, $props, 'succeeding-typeResolver')) {
            foreach ($this->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
                /**
                 * If passing a subcomponent fieldname that doesn't exist to the API,
                 * then $subcomponent_typeResolver_class will be empty.
                 *
                 * If there is an error in the query, eg: `{ id { id } }`,
                 * it was already added in `initModelProps`
                 */
                $typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($relationalTypeResolver, $relationalComponentFieldNode->getField());
                if ($typeResolver === null) {
                    continue;
                }
                /** @var FieldInterface[] */
                $relationalPathFields = \array_merge($pathFields, [$relationalComponentFieldNode->getField()]);
                $relationalPathFieldOutputKeys = \array_map(function (FieldInterface $field) {
                    return $field->getOutputKey();
                }, $relationalPathFields);
                $ret[\implode(Constants::RELATIONAL_FIELD_PATH_SEPARATOR, $relationalPathFieldOutputKeys)] = $typeResolver->getTypeOutputKey();
            }
            foreach ($this->getConditionalRelationalComponentFieldNodes($component) as $conditionalRelationalComponentFieldNode) {
                foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $relationalComponentFieldNode) {
                    /**
                     * If passing a subcomponent fieldname that doesn't exist to the API,
                     * then $subcomponent_typeResolver_class will be empty.
                     *
                     * If there is an error in the query, eg: `{ id { id } }`,
                     * it was already added in `initModelProps`
                     */
                    $typeResolver = $this->getDataloadHelperService()->getTypeResolverFromSubcomponentField($relationalTypeResolver, $relationalComponentFieldNode->getField());
                    if ($typeResolver === null) {
                        /**
                         * This is an error in the query, eg: `{ id { id } }`,
                         * but the error was already added in `initModelProps`
                         */
                        continue;
                    }
                    /** @var FieldInterface[] */
                    $relationalPathFields = \array_merge($pathFields, [$relationalComponentFieldNode->getField()]);
                    $relationalPathFieldOutputKeys = \array_map(function (FieldInterface $field) {
                        return $field->getOutputKey();
                    }, $relationalPathFields);
                    $ret[\implode(Constants::RELATIONAL_FIELD_PATH_SEPARATOR, $relationalPathFieldOutputKeys)] = $typeResolver->getTypeOutputKey();
                }
            }
        }
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetOutputKeys($component, &$props) : array
    {
        $ret = array();
        $this->addToDatasetOutputKeys($component, $props, [], $ret);
        return $ret;
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Static + Stateful Data
    //-------------------------------------------------
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasource($component, &$props) : string
    {
        // Each component can only return one piece of data, and it must be indicated if it static or mutableonrequest
        // Retrieving only 1 piece is needed so that its children do not get confused what data their getLeafComponentFieldNodes applies to
        return DataSources::MUTABLEONREQUEST;
    }
    /**
     * @return string|int|array<string|int>|null
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getObjectIDOrIDs($component, &$props, &$data_properties)
    {
        return [];
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalTypeResolver($component) : ?RelationalTypeResolverInterface
    {
        return null;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function doesComponentLoadData($component) : bool
    {
        return $this->getRelationalTypeResolver($component) !== null;
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function startDataloadingSection($component) : bool
    {
        return $this->doesComponentLoadData($component);
    }
    /**
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentMutationResolverBridge($component) : ?ComponentMutationResolverBridgeInterface
    {
        return null;
    }
    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function prepareDataPropertiesAfterMutationExecution($component, &$props, &$data_properties) : void
    {
        // Do nothing
    }
    /**
     * @return LeafComponentFieldNode[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getLeafComponentFieldNodes($component, &$props) : array
    {
        return [];
    }
    /**
     * @return RelationalComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getRelationalComponentFieldNodes($component) : array
    {
        return [];
    }
    /**
     * @return ConditionalLeafComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getConditionalLeafComponentFieldNodes($component) : array
    {
        return [];
    }
    /**
     * @return ConditionalRelationalComponentFieldNode[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getConditionalRelationalComponentFieldNodes($component) : array
    {
        return [];
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Data Properties
    //-------------------------------------------------
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDataPropertiesDatasetcomponentTree($component, &$props) : array
    {
        // The data-properties start on a dataloading component, and finish on the next dataloding component down the line
        // This way, we can collect all the data-fields that the component will need to load for its dbobjects
        return $this->executeOnSelfAndPropagateToComponents('getImmutableDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, \false);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array
    {
        $ret = array();
        // Only if this component loads data => We are at the head nodule of the dataset section
        if ($this->doesComponentLoadData($component)) {
            // Load the data-fields from all components inside this section
            // And then, only for the top node, add its extra properties
            $properties = \array_merge($this->getDatasetComponentTreeSectionFlattenedDataProperties($component, $props), $this->getImmutableHeaddatasetcomponentDataProperties($component, $props));
            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetComponentTreeSectionFlattenedDataProperties($component, &$props) : array
    {
        $ret = array();
        /**
         * Calculate the data-fields from merging them with the
         * subcomponent components' keys, which are data-fields too.
         */
        if ($componentFieldNodes = $this->getComponentFieldNodes($component, $props)) {
            $ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = $componentFieldNodes;
        }
        // Propagate down to the components
        $this->flattenDatasetcomponentTreeDataProperties(__FUNCTION__, $ret, $component, $props);
        // Propagate down to the subcomponent components
        $this->flattenRelationalDBObjectDataProperties(__FUNCTION__, $ret, $component, $props);
        return $ret;
    }
    /**
     * @return ComponentFieldNodeInterface[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function getComponentFieldNodes($component, &$props) : array
    {
        return \array_merge($this->getLeafComponentFieldNodes($component, $props), $this->getRelationalComponentFieldNodes($component), $this->getConditionalLeafComponentFieldNodes($component), $this->getConditionalRelationalComponentFieldNodes($component));
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDatasetcomponentTreeSectionFlattenedComponents($component) : array
    {
        $ret = [];
        $this->addDatasetcomponentTreeSectionFlattenedComponents($ret, $component);
        return \array_values(\array_unique($ret, \SORT_REGULAR));
    }
    /**
     * @param Component[] $ret
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function addDatasetcomponentTreeSectionFlattenedComponents(&$ret, $component) : void
    {
        $ret[] = $component;
        // Propagate down to the components
        // $this->flattenDatasetcomponentTreeComponents(__FUNCTION__, $ret, $component);
        // Exclude the subcomponent components here
        if ($subcomponents = $this->getComponentsToPropagateDataProperties($component)) {
            foreach ($subcomponents as $subcomponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
                // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                if ($subcomponent_processor->startDataloadingSection($subcomponent)) {
                    continue;
                }
                $subcomponent_processor->addDatasetcomponentTreeSectionFlattenedComponents($ret, $subcomponent);
            }
        }
    }
    // protected function flattenDatasetcomponentTreeComponents($propagate_fn, &$ret, \PoP\ComponentModel\Component\Component $component)
    // {
    //     // Exclude the subcomponent components here
    //     if ($subcomponents = $this->getComponentsToPropagateDataProperties($component)) {
    //         foreach ($subcomponents as $subcomponent) {
    //             $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
    //             // Propagate only if the subcomponent doesn't have a typeResolver. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
    //             if (!$subcomponent_processor->startDataloadingSection($subcomponent)) {
    //                 if ($subcomponent_ret = $subcomponent_processor->$propagate_fn($subcomponent)) {
    //                     $ret = array_merge(
    //                         $ret,
    //                         $subcomponent_ret
    //                     );
    //                 }
    //             }
    //         }
    //     }
    // }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getImmutableHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        // By default return nothing at the last level
        $ret = array();
        // From the State property we find out if it's Static of Stateful
        $datasource = $this->getDatasource($component, $props);
        $ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::DATASOURCE] = $datasource;
        // Add the properties below either as static or mutableonrequest
        if ($datasource == DataSources::IMMUTABLE) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }
        return $ret;
    }
    /**
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function addHeaddatasetcomponentDataProperties(&$ret, $component, &$props) : void
    {
        /**
         * Allow to add more stuff
         */
        App::doAction(self::HOOK_ADD_HEADDATASETCOMPONENT_DATAPROPERTIES, array(&$ret), $component, array(&$props), $this);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelDataPropertiesDatasetcomponentTree($component, &$props) : array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, \false);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array
    {
        $ret = array();
        // Only if this component loads data
        if ($this->doesComponentLoadData($component)) {
            $properties = $this->getMutableonmodelHeaddatasetcomponentDataProperties($component, $props);
            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonmodelHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        $ret = array();
        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($component, $props);
        if ($datasource == DataSources::MUTABLEONMODEL) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }
        // Fetch params from request?
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            $ignore_params_from_request = \true;
        } else {
            $ignore_params_from_request = $this->getProp($component, $props, 'ignore-request-params');
        }
        if ($ignore_params_from_request !== null) {
            $ret[\PoP\ComponentModel\ComponentProcessors\DataloadingConstants::IGNOREREQUESTPARAMS] = $ignore_params_from_request;
        }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestDataPropertiesDatasetcomponentTree($component, &$props) : array
    {
        return $this->executeOnSelfAndPropagateToComponents('getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection', __FUNCTION__, $component, $props, \false);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestDataPropertiesDatasetcomponentTreeFullsection($component, &$props) : array
    {
        $ret = array();
        // Only if this component loads data
        if ($this->doesComponentLoadData($component)) {
            // // Load the data-fields from all modules inside this section
            // // And then, only for the top node, add its extra properties
            // $properties = array_merge(
            //     $this->get_mutableonrequest_data_properties_datasetcomponentTree_section($component, $props),
            //     $this->getMutableonrequestHeaddatasetcomponentDataProperties($component, $props)
            // );
            $properties = $this->getMutableonrequestHeaddatasetcomponentDataProperties($component, $props);
            if ($properties) {
                $ret[DataLoading::DATA_PROPERTIES] = $properties;
            }
        }
        return $ret;
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestHeaddatasetcomponentDataProperties($component, &$props) : array
    {
        $ret = array();
        // Add the properties below either as static or mutableonrequest
        $datasource = $this->getDatasource($component, $props);
        if ($datasource == DataSources::MUTABLEONREQUEST) {
            $this->addHeaddatasetcomponentDataProperties($ret, $component, $props);
        }
        // When loading data or execution an action, check if to validate checkpoints?
        // This is in MUTABLEONREQUEST instead of STATIC because the checkpoints can change depending on doingPost()
        // (such as done to set-up checkpoint configuration for POP_USERSTANCE_ROUTE_ADDOREDITSTANCE, or within POPUSERLOGIN_CHECKPOINTCONFIGURATION_REQUIREUSERSTATEONDOINGPOST)
        if ($checkpoints = $this->getDataAccessCheckpoints($component, $props)) {
            $ret[DataLoading::DATA_ACCESS_CHECKPOINTS] = $checkpoints;
        }
        // To trigger the actionexecuter, its own checkpoints must be successful
        if ($checkpoints = $this->getActionExecutionCheckpoints($component, $props)) {
            $ret[DataLoading::ACTION_EXECUTION_CHECKPOINTS] = $checkpoints;
        }
        return $ret;
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Data Feedback
    //-------------------------------------------------
    /**
     * @return array<string|int,mixed>
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getDataFeedbackDatasetcomponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array
    {
        return $this->executeOnSelfAndPropagateToDatasetComponents('getDataFeedbackComponentTree', __FUNCTION__, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
    }
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
    public function getDataFeedbackComponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array
    {
        $ret = array();
        if ($feedback = $this->getDataFeedback($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs)) {
            $ret[DataLoading::FEEDBACK] = $feedback;
        }
        return $ret;
    }
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
    public function getDataFeedback($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array
    {
        return [];
    }
    /**
     * @return mixed[]|null
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataFeedbackInterreferencedComponentPath($component, &$props) : ?array
    {
        return null;
    }
    //-------------------------------------------------
    // Background URLs
    //-------------------------------------------------
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
    public function getBackgroundurlsMergeddatasetcomponentTree($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array
    {
        return $this->executeOnSelfAndMergeWithDatasetComponents('getBackgroundurls', __FUNCTION__, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs);
    }
    /**
     * @return string[]
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param array<string|int> $objectIDs
     * @param array<string,mixed>|null $executed
     * @param \PoP\ComponentModel\Component\Component $component
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $dataaccess_checkpoint_validation
     * @param \PoP\Root\Feedback\FeedbackItemResolution|null $actionexecution_checkpoint_validation
     */
    public function getBackgroundurls($component, &$props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDs) : array
    {
        return [];
    }
    //-------------------------------------------------
    // Dataset Meta
    //-------------------------------------------------
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
        return [];
    }
    //-------------------------------------------------
    // Others
    //-------------------------------------------------
    /**
     * @return CheckpointInterface[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getDataAccessCheckpoints($component, &$props) : array
    {
        return [];
    }
    /**
     * @return CheckpointInterface[]
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getActionExecutionCheckpoints($component, &$props) : array
    {
        return [];
    }
    /**
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function shouldExecuteMutation($component, &$props) : bool
    {
        // By default, execute only if the component is targeted for execution and doing POST
        return 'POST' === App::server('REQUEST_METHOD') && App::getState('actionpath') === $this->getComponentPathHelpers()->getStringifiedModulePropagationCurrentPath($component);
    }
    /**
     * @return Component[]
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getComponentsToPropagateDataProperties($component) : array
    {
        return $this->getSubcomponentsByGroup($component, array(self::COMPONENTELEMENT_SUBCOMPONENTS, self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS));
    }
    /**
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $props
     * @param string $propagate_fn
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function flattenDatasetcomponentTreeDataProperties($propagate_fn, &$ret, $component, &$props) : void
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        // Exclude the subcomponent components here
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        if ($subcomponents = $this->getComponentsToPropagateDataProperties($component)) {
            // Calculate in 2 steps:
            // First step: The conditional-on-data-field-subcomponents must have their data-fields added under entry "conditional-fields"
            $conditionalLeafComponentFieldNodes = $this->getConditionalLeafComponentFieldNodes($component);
            $conditionalRelationalComponentFieldNodes = $this->getConditionalRelationalComponentFieldNodes($component);
            if ($conditionalLeafComponentFieldNodes !== [] || $conditionalRelationalComponentFieldNodes !== []) {
                $directSubcomponents = $this->getSubcomponents($component);
                $conditionalComponentFieldNodes = new SplObjectStorage();
                // Instead of assigning to $ret, first assign it to a temporary variable, so we can then replace 'direct-component-field-nodes' with 'conditional-component-field-nodes' before merging to $ret
                foreach ($conditionalLeafComponentFieldNodes as $conditionalLeafComponentFieldNode) {
                    $conditionalComponentFieldNodes[$conditionalLeafComponentFieldNode] = $conditionalLeafComponentFieldNode->getConditionalNestedComponents();
                }
                foreach ($conditionalRelationalComponentFieldNodes as $conditionalRelationalComponentFieldNode) {
                    $subconditionalComponentFieldNodes = [];
                    foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $subConditionalRelationalComponentFieldNode) {
                        $conditionalSubcomponents = $subConditionalRelationalComponentFieldNode->getNestedComponents();
                        $subconditionalComponentFieldNodes = \array_merge($subconditionalComponentFieldNodes, $conditionalSubcomponents);
                    }
                    $conditionalComponentFieldNodes[$conditionalRelationalComponentFieldNode] = $subconditionalComponentFieldNodes;
                }
                /** @var ComponentFieldNodeInterface $conditionComponentFieldNode */
                foreach ($conditionalComponentFieldNodes as $conditionComponentFieldNode) {
                    /** @var Component[] */
                    $conditionalSubcomponents = $conditionalComponentFieldNodes[$conditionComponentFieldNode];
                    // Calculate those fields which are certainly to be propagated, and not part of the direct subcomponents
                    // Using this really ugly way because, for comparing components, using `array_diff` and `intersect` fail
                    for ($i = \count($conditionalSubcomponents) - 1; $i >= 0; $i--) {
                        // If this subcomponent is also in the direct ones, then it's not conditional anymore
                        if (\in_array($conditionalSubcomponents[$i], $directSubcomponents)) {
                            \array_splice($conditionalSubcomponents, $i, 1);
                        }
                    }
                    foreach ($conditionalSubcomponents as $subcomponent) {
                        $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
                        // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                        if ($subcomponent_processor->startDataloadingSection($subcomponent)) {
                            continue;
                        }
                        $subcomponent_ret = $subcomponent_processor->{$propagate_fn}($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS]);
                        if (!$subcomponent_ret) {
                            continue;
                        }
                        // Chain the "direct-fields" from the sublevels under the current "conditional-fields"
                        // Move from "direct-fields" to "conditional-fields"
                        $ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] = $ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? new SplObjectStorage();
                        /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]> */
                        $conditionalComponentFieldSplObjectStorage = $ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES];
                        /** @var ComponentFieldNodeInterface[]|null */
                        $subcomponent_direct_fields = $subcomponent_ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? null;
                        if ($subcomponent_direct_fields !== null) {
                            $conditionalComponentFieldSplObjectStorage[$conditionComponentFieldNode] = \array_merge($conditionalComponentFieldSplObjectStorage[$conditionComponentFieldNode] ?? [], $subcomponent_direct_fields);
                            unset($subcomponent_ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES]);
                        }
                        // Chain the conditional-fields at the end of the one from this component
                        /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]>|null */
                        $subcomponentConditionalFieldSplObjectStorage = $subcomponent_ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? null;
                        if ($subcomponentConditionalFieldSplObjectStorage !== null) {
                            foreach ($subcomponentConditionalFieldSplObjectStorage as $subcomponentComponentFieldNode) {
                                /** @var ComponentFieldNodeInterface[] */
                                $subcomponent_conditional_fields = $subcomponentConditionalFieldSplObjectStorage[$subcomponentComponentFieldNode];
                                $conditionalComponentFieldSplObjectStorage[$subcomponentComponentFieldNode] = \array_merge($conditionalComponentFieldSplObjectStorage[$subcomponentComponentFieldNode] ?? [], $subcomponent_conditional_fields);
                            }
                            unset($subcomponent_ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES]);
                        }
                        $ret[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] = $conditionalComponentFieldSplObjectStorage;
                        /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>>|null */
                        $subcomponentSubcomponentsSplObjectStorage = $subcomponent_ret[DataProperties::SUBCOMPONENTS] ?? null;
                        if ($subcomponentSubcomponentsSplObjectStorage !== null) {
                            $ret[DataProperties::SUBCOMPONENTS] = $ret[DataProperties::SUBCOMPONENTS] ?? new SplObjectStorage();
                            $ret[DataProperties::SUBCOMPONENTS]->addAll($subcomponentSubcomponentsSplObjectStorage);
                        }
                    }
                    // Extract the conditional subcomponents from the rest of the subcomponents, which will be processed below
                    foreach ($conditionalSubcomponents as $conditionalSubcomponent) {
                        $pos = \array_search($conditionalSubcomponent, $subcomponents);
                        if ($pos === \false) {
                            continue;
                        }
                        /** @var int $pos  */
                        \array_splice($subcomponents, $pos, 1);
                    }
                }
            }
            // Second step: all the other subcomponents can be calculated directly
            foreach ($subcomponents as $subcomponent) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent);
                // Propagate only if the subcomponent doesn't load data. If it does, this is the end of the data line, and the subcomponent is the beginning of a new datasetcomponentTree
                if ($subcomponent_processor->startDataloadingSection($subcomponent)) {
                    continue;
                }
                $subcomponent_ret = $subcomponent_processor->{$propagate_fn}($subcomponent, $props[$componentFullName][Props::SUBCOMPONENTS]);
                if (!$subcomponent_ret) {
                    continue;
                }
                /**
                 * @todo Fix `array_merge_recursive` here, since `SplObjectStorage` entries
                 *       (under 'subcomponents' and 'conditional-component-field-nodes') will not get merged.
                 *       This code is not being called for the GraphQL server, but will for the
                 *       SiteBuilder, so check and fix.
                 */
                // array_merge_recursive => data-fields from different sidebar-components can be integrated all together
                $ret = \array_merge_recursive($ret, $subcomponent_ret);
            }
            // Array Merge appends values when under numeric keys, so we gotta filter duplicates out
            if ($ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? null) {
                $ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = \array_values(\array_unique($ret[DataProperties::DIRECT_COMPONENT_FIELD_NODES]));
            }
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }
    /**
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $props
     * @param string $propagate_fn
     * @param \PoP\ComponentModel\Component\Component $component
     */
    protected function flattenRelationalDBObjectDataProperties($propagate_fn, &$ret, $component, &$props) : void
    {
        $componentFullName = $this->getComponentHelpers()->getComponentFullName($component);
        // Combine the direct and conditionalOnDataField components all together to iterate below
        $relationalSubcomponents = new SplObjectStorage();
        foreach ($this->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
            $relationalSubcomponents[$relationalComponentFieldNode] = \array_merge($relationalSubcomponents[$relationalComponentFieldNode] ?? [], $relationalComponentFieldNode->getNestedComponents());
        }
        foreach ($this->getConditionalRelationalComponentFieldNodes($component) as $conditionalRelationalComponentFieldNode) {
            foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $relationalComponentFieldNode) {
                $relationalSubcomponents[$relationalComponentFieldNode] = \array_merge($relationalSubcomponents[$relationalComponentFieldNode] ?? [], $relationalComponentFieldNode->getNestedComponents());
            }
        }
        // If it has subcomponent components, integrate them under 'subcomponents'
        $this->getComponentFilterManager()->prepareForPropagation($component, $props);
        /** @var ComponentFieldNodeInterface $subcomponentComponentFieldNode */
        foreach ($relationalSubcomponents as $subcomponentComponentFieldNode) {
            /** @var Component[] */
            $subcomponent_components = $relationalSubcomponents[$subcomponentComponentFieldNode];
            $subcomponent_components_data_properties = [DataProperties::DIRECT_COMPONENT_FIELD_NODES => [], DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES => new SplObjectStorage(), DataProperties::SUBCOMPONENTS => new SplObjectStorage()];
            foreach ($subcomponent_components as $subcomponent_component) {
                $subcomponent_processor = $this->getComponentProcessorManager()->getComponentProcessor($subcomponent_component);
                $subcomponent_component_data_properties = $subcomponent_processor->{$propagate_fn}($subcomponent_component, $props[$componentFullName][Props::SUBCOMPONENTS]);
                if (!$subcomponent_component_data_properties) {
                    continue;
                }
                if ($subcomponent_component_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? null) {
                    $subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = \array_merge($subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES], $subcomponent_component_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES]);
                }
                /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]>|null */
                $subcomponentConditionalFields = $subcomponent_component_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? null;
                if ($subcomponentConditionalFields !== null) {
                    /** @var ComponentFieldNodeInterface $conditionComponentFieldNode */
                    foreach ($subcomponentConditionalFields as $conditionComponentFieldNode) {
                        /** @var ComponentFieldNodeInterface[] */
                        $conditionalComponentFieldSplObjectStorage = $subcomponentConditionalFields[$conditionComponentFieldNode];
                        $subcomponent_components_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionComponentFieldNode] = \array_merge($subcomponent_components_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionComponentFieldNode] ?? [], $conditionalComponentFieldSplObjectStorage);
                    }
                }
                /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>>|null */
                $splObjectStorage = $subcomponent_component_data_properties[DataProperties::SUBCOMPONENTS] ?? null;
                if ($splObjectStorage !== null) {
                    /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>> */
                    $subcomponent_components_data_properties_storage = $subcomponent_components_data_properties[DataProperties::SUBCOMPONENTS];
                    $subcomponent_components_data_properties_storage->addAll($splObjectStorage);
                    $subcomponent_components_data_properties[DataProperties::SUBCOMPONENTS] = $subcomponent_components_data_properties_storage;
                }
            }
            $ret[DataProperties::SUBCOMPONENTS] = $ret[DataProperties::SUBCOMPONENTS] ?? new SplObjectStorage();
            $ret[DataProperties::SUBCOMPONENTS][$subcomponentComponentFieldNode] = $ret[DataProperties::SUBCOMPONENTS][$subcomponentComponentFieldNode] ?? [];
            $subcomponentsSubcomponentFieldNode = $ret[DataProperties::SUBCOMPONENTS][$subcomponentComponentFieldNode];
            if ($subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES]) {
                $subcomponentsSubcomponentFieldNode[DataProperties::DIRECT_COMPONENT_FIELD_NODES] = \array_values(\array_unique(\array_merge($subcomponentsSubcomponentFieldNode[DataProperties::DIRECT_COMPONENT_FIELD_NODES] ?? [], $subcomponent_components_data_properties[DataProperties::DIRECT_COMPONENT_FIELD_NODES])));
            }
            /** @var SplObjectStorage<ComponentFieldNodeInterface,ComponentFieldNodeInterface[]> */
            $subcomponentConditionalFields = $subcomponent_components_data_properties[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES];
            if ($subcomponentConditionalFields->count() > 0) {
                $subcomponentsSubcomponentFieldNode[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] = $subcomponentsSubcomponentFieldNode[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES] ?? new SplObjectStorage();
                /** @var ComponentFieldNodeInterface $conditionComponentFieldNode */
                foreach ($subcomponentConditionalFields as $conditionComponentFieldNode) {
                    /** @var ComponentFieldNodeInterface[] */
                    $conditionalComponentFieldSplObjectStorage = $subcomponentConditionalFields[$conditionComponentFieldNode];
                    $subcomponentsSubcomponentFieldNode[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionComponentFieldNode] = \array_merge($subcomponentsSubcomponentFieldNode[DataProperties::CONDITIONAL_COMPONENT_FIELD_NODES][$conditionComponentFieldNode] ?? [], $conditionalComponentFieldSplObjectStorage);
                }
            }
            /** @var SplObjectStorage<ComponentFieldNodeInterface,array<string,mixed>> */
            $splObjectStorage = $subcomponent_components_data_properties[DataProperties::SUBCOMPONENTS];
            if ($splObjectStorage->count() > 0) {
                $subcomponentsSubcomponentFieldNode[DataProperties::SUBCOMPONENTS] = $subcomponentsSubcomponentFieldNode[DataProperties::SUBCOMPONENTS] ?? new SplObjectStorage();
                $subcomponentsSubcomponentFieldNode[DataProperties::SUBCOMPONENTS]->addAll($splObjectStorage);
            }
            $ret[DataProperties::SUBCOMPONENTS][$subcomponentComponentFieldNode] = $subcomponentsSubcomponentFieldNode;
        }
        $this->getComponentFilterManager()->restoreFromPropagation($component, $props);
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Static Data
    //-------------------------------------------------
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelSupplementaryDBObjectDataComponentTree($component, &$props) : array
    {
        return $this->executeOnSelfAndMergeWithComponents('getModelSupplementaryDBObjectData', __FUNCTION__, $component, $props);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getModelSupplementaryDBObjectData($component, &$props) : array
    {
        return [];
    }
    //-------------------------------------------------
    // New PUBLIC Functions: Stateful Data
    //-------------------------------------------------
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestSupplementaryDBObjectDataComponentTree($component, &$props) : array
    {
        return $this->executeOnSelfAndMergeWithComponents('getMutableonrequestSupplementaryDbobjectdata', __FUNCTION__, $component, $props);
    }
    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $props
     * @param \PoP\ComponentModel\Component\Component $component
     */
    public function getMutableonrequestSupplementaryDbobjectdata($component, &$props) : array
    {
        return [];
    }
    /**
     * @return Component[]
     * @param string[] $elements
     */
    private function getSubcomponentsByGroup(Component $component, array $elements = array()) : array
    {
        if (empty($elements)) {
            $elements = array(self::COMPONENTELEMENT_SUBCOMPONENTS, self::COMPONENTELEMENT_RELATIONALSUBCOMPONENTS, self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS, self::COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS);
        }
        $components = array();
        if (\in_array(self::COMPONENTELEMENT_SUBCOMPONENTS, $elements)) {
            $components = $this->getSubcomponents($component);
        }
        if (\in_array(self::COMPONENTELEMENT_RELATIONALSUBCOMPONENTS, $elements)) {
            foreach ($this->getRelationalComponentFieldNodes($component) as $relationalComponentFieldNode) {
                $components = \array_merge($components, $relationalComponentFieldNode->getNestedComponents());
            }
        }
        if (\in_array(self::COMPONENTELEMENT_CONDITIONALONDATAFIELDSUBCOMPONENTS, $elements)) {
            foreach ($this->getConditionalLeafComponentFieldNodes($component) as $conditionalLeafComponentFieldNode) {
                $components = \array_merge($components, $conditionalLeafComponentFieldNode->getConditionalNestedComponents());
            }
        }
        if (\in_array(self::COMPONENTELEMENT_CONDITIONALONDATAFIELDRELATIONALSUBCOMPONENTS, $elements)) {
            foreach ($this->getConditionalRelationalComponentFieldNodes($component) as $conditionalRelationalComponentFieldNode) {
                foreach ($conditionalRelationalComponentFieldNode->getRelationalComponentFieldNodes() as $relationalComponentFieldNode) {
                    $components = \array_merge($components, $relationalComponentFieldNode->getNestedComponents());
                }
            }
        }
        return \array_values(\array_unique($components, \SORT_REGULAR));
    }
}
