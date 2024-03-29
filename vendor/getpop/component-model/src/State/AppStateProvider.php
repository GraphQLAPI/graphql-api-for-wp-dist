<?php

declare (strict_types=1);
namespace PoP\ComponentModel\State;

use PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface;
use PoP\ComponentModel\Configuration\EngineRequest;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Variables\VariableManagerInterface;
use PoP\Definitions\Configuration\Request as DefinitionsRequest;
use PoP\Definitions\Constants\ParamValues;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;
use SplObjectStorage;
class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @var \PoP\ComponentModel\Variables\VariableManagerInterface|null
     */
    private $fieldQueryInterpreter;
    /**
     * @var \PoP\ComponentModel\ComponentFiltering\ComponentFilterManagerInterface|null
     */
    private $componentFilterManager;
    /**
     * @var \PoP\ComponentModel\Engine\EngineInterface|null
     */
    private $engine;
    /**
     * @param \PoP\ComponentModel\Variables\VariableManagerInterface $fieldQueryInterpreter
     */
    public final function setVariableManager($fieldQueryInterpreter) : void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    protected final function getVariableManager() : VariableManagerInterface
    {
        /** @var VariableManagerInterface */
        return $this->fieldQueryInterpreter = $this->fieldQueryInterpreter ?? $this->instanceManager->getInstance(VariableManagerInterface::class);
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
     * @param \PoP\ComponentModel\Engine\EngineInterface $engine
     */
    public final function setEngine($engine) : void
    {
        $this->engine = $engine;
    }
    protected final function getEngine() : EngineInterface
    {
        /** @var EngineInterface */
        return $this->engine = $this->engine ?? $this->instanceManager->getInstance(EngineInterface::class);
    }
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(&$state) : void
    {
        // For Serialization
        /** @var SplObjectStorage<FieldInterface,int|null> */
        $fieldTypeModifiersForSerialization = new SplObjectStorage();
        $state['field-type-modifiers-for-serialization'] = $fieldTypeModifiersForSerialization;
        // For Validating if the Directive supports only certain types
        $state['field-type-resolver-for-supported-directive-resolution'] = null;
        $state['componentFilter'] = $this->getComponentFilterManager()->getSelectedComponentFilterName();
        $state['variables'] = $this->getVariableManager()->getVariablesFromRequest();
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $state['mangled'] = DefinitionsRequest::getMangledValue();
            $state['actionpath'] = Request::getActionPath();
            $state['actions'] = Request::getActions();
            $state['version-constraint'] = Request::getVersionConstraint();
            $state['field-version-constraints'] = Request::getVersionConstraintsForFields();
            $state['directive-version-constraints'] = Request::getVersionConstraintsForDirectives();
        } else {
            $state['mangled'] = ParamValues::MANGLED_NONE;
            $state['actionpath'] = null;
            $state['actions'] = [];
            $state['version-constraint'] = null;
            $state['field-version-constraints'] = null;
            $state['directive-version-constraints'] = null;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $moduleConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['output'] = EngineRequest::getOutput($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputitems'] = EngineRequest::getDataOutputItems($enableModifyingEngineBehaviorViaRequest);
        $state['datasourceselector'] = EngineRequest::getDataSourceSelector($enableModifyingEngineBehaviorViaRequest);
        $state['datastructure'] = EngineRequest::getDataStructure($enableModifyingEngineBehaviorViaRequest);
        $state['dataoutputmode'] = EngineRequest::getDataOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['dboutputmode'] = EngineRequest::getDBOutputMode($enableModifyingEngineBehaviorViaRequest);
        $state['scheme'] = EngineRequest::getScheme($enableModifyingEngineBehaviorViaRequest);
    }
    /**
     * Must initialize the Engine state before parsing the GraphQL query in:
     *
     * @see layers/API/packages/api/src/State/AppStateProvider.php
     *
     * Otherwise, if there's an error (eg: empty query), it throws
     * an exception when adding it to the FeedbackStore.     *
     *
     * Call ModuleConfiguration only after hooks from
     * SchemaConfigurationExecuter have been initialized.
     * That's why these are called on `execute` and not `initialize`.
     * @param array<string,mixed> $state
     */
    public function execute(&$state) : void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $state['namespace-types-and-interfaces'] = $moduleConfiguration->mustNamespaceTypes();
        $this->getEngine()->initializeState();
    }
}
