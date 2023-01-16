<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\Standalone;

use GraphQLByPoP\GraphQLServer\Module;
use PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface;
use PoPAPI\API\Response\Schemes;
use PoPAPI\API\Routing\RequestNature;
use PoPAPI\GraphQLAPI\DataStructureFormatters\GraphQLDataStructureFormatter;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Feedback\DocumentFeedback;
use PoP\ComponentModel\Feedback\QueryFeedback;
use PoP\GraphQLParser\Exception\AbstractASTNodeException;
use PoP\GraphQLParser\Exception\AbstractQueryException;
use PoP\GraphQLParser\Exception\Parser\AbstractASTNodeParserException;
use PoP\GraphQLParser\Exception\Parser\AbstractParserException;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\HttpFoundation\Response;
use PoP\Root\Module\ModuleInterface;
use PoP\Root\Services\StandaloneServiceTrait;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
class GraphQLServer implements \GraphQLByPoP\GraphQLServer\Standalone\GraphQLServerInterface
{
    use StandaloneServiceTrait;
    /**
     * @var array<class-string<ModuleInterface>>
     * @readonly
     */
    private $moduleClasses;
    /**
     * @var \PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface|null
     */
    private $graphQLParserHelperService;
    /**
     * @var array<class-string<ModuleInterface>, array<string, mixed>>
     * @readonly
     */
    private $moduleClassConfiguration = [];
    /**
     * @var array<class-string<CompilerPassInterface>>
     * @readonly
     */
    private $systemContainerCompilerPassClasses = [];
    /**
     * @var array<class-string<CompilerPassInterface>>
     * @readonly
     */
    private $applicationContainerCompilerPassClasses = [];
    /**
     * @readonly
     * @var bool|null
     */
    private $cacheContainerConfiguration;
    /**
     * @readonly
     * @var string|null
     */
    private $containerNamespace;
    /**
     * @readonly
     * @var string|null
     */
    private $containerDirectory;
    /**
     * @param \PoPAPI\API\QueryParsing\GraphQLParserHelperServiceInterface $graphQLParserHelperService
     */
    public final function setGraphQLParserHelperService($graphQLParserHelperService) : void
    {
        $this->graphQLParserHelperService = $graphQLParserHelperService;
    }
    protected final function getGraphQLParserHelperService() : GraphQLParserHelperServiceInterface
    {
        /** @var GraphQLParserHelperServiceInterface */
        return $this->graphQLParserHelperService = $this->graphQLParserHelperService ?? InstanceManagerFacade::getInstance()->getInstance(GraphQLParserHelperServiceInterface::class);
    }
    /**
     * @param array<class-string<ModuleInterface>> $moduleClasses The component classes to initialize, including those dealing with the schema elements (posts, users, comments, etc)
     * @param array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration Predefined configuration for the components
     * @param array<class-string<CompilerPassInterface>> $systemContainerCompilerPassClasses
     * @param array<class-string<CompilerPassInterface>> $applicationContainerCompilerPassClasses
     */
    public function __construct(array $moduleClasses, array $moduleClassConfiguration = [], array $systemContainerCompilerPassClasses = [], array $applicationContainerCompilerPassClasses = [], ?bool $cacheContainerConfiguration = null, ?string $containerNamespace = null, ?string $containerDirectory = null)
    {
        $this->moduleClassConfiguration = $moduleClassConfiguration;
        $this->systemContainerCompilerPassClasses = $systemContainerCompilerPassClasses;
        $this->applicationContainerCompilerPassClasses = $applicationContainerCompilerPassClasses;
        $this->cacheContainerConfiguration = $cacheContainerConfiguration;
        $this->containerNamespace = $containerNamespace;
        $this->containerDirectory = $containerDirectory;
        $this->moduleClasses = \array_merge($moduleClasses, [
            // This is the one Module that is required to produce the GraphQL server.
            // The other classes provide the schema and extra functionality.
            Module::class,
        ]);
        $this->initializeApp();
        $appLoader = App::getAppLoader();
        $appLoader->addModuleClassesToInitialize($this->moduleClasses);
        $appLoader->initializeModules();
        // Inject the Compiler Passes
        $appLoader->addSystemContainerCompilerPassClasses($this->systemContainerCompilerPassClasses);
        $appLoader->bootSystem($this->cacheContainerConfiguration, $this->containerNamespace, $this->containerDirectory);
        // Only after initializing the System Container,
        // we can obtain the configuration (which may depend on hooks)
        $appLoader->addModuleClassConfiguration($this->moduleClassConfiguration);
        // Inject the Compiler Passes
        $appLoader->addApplicationContainerCompilerPassClasses($this->applicationContainerCompilerPassClasses);
        // Boot the application
        $appLoader->bootApplication($this->cacheContainerConfiguration, $this->containerNamespace, $this->containerDirectory);
        // After booting the application, we can access the Application Container services
        // Explicitly set the required state to execute GraphQL queries
        $appLoader->setInitialAppState($this->getGraphQLRequestAppState());
        // Finally trigger booting the components
        $appLoader->bootApplicationModules();
    }
    protected function initializeApp() : void
    {
        App::initialize();
    }
    /**
     * The required state to execute GraphQL queries.
     *
     * @return array<string,mixed>
     */
    protected function getGraphQLRequestAppState() : array
    {
        return ['scheme' => Schemes::API, 'datastructure' => $this->getGraphQLDataStructureFormatter()->getName(), 'nature' => RequestNature::QUERY_ROOT, 'query' => null];
    }
    protected function getGraphQLDataStructureFormatter() : GraphQLDataStructureFormatter
    {
        /** @var GraphQLDataStructureFormatter */
        return App::getContainer()->get(GraphQLDataStructureFormatter::class);
    }
    /**
     * The basic state for executing GraphQL queries is already set.
     * In addition, inject the actual GraphQL query and variables,
     * build the AST, and generate and print the data.
     *
     * @param array<string,mixed> $variables
     * @param string|\PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument $queryOrExecutableDocument
     * @param string|null $operationName
     */
    public function execute($queryOrExecutableDocument, $variables = [], $operationName = null) : Response
    {
        // Override the previous response, if any
        App::regenerateResponse();
        $engine = EngineFacade::getInstance();
        $engine->initializeState();
        $passingQuery = \is_string($queryOrExecutableDocument);
        if ($passingQuery) {
            $query = $queryOrExecutableDocument;
            $executableDocument = null;
        } else {
            $executableDocument = $queryOrExecutableDocument;
            $query = $executableDocument->getDocument()->asDocumentString();
        }
        // Override the state
        $appStateManager = App::getAppStateManager();
        $appStateManager->override('query', $query);
        $appStateManager->override('variables', $variables);
        $appStateManager->override('document-dynamic-variables', []);
        $appStateManager->override('operation-name', $operationName);
        $appStateManager->override('does-api-query-have-errors', null);
        $appStateManager->override('executable-document-ast-field-fragmentmodels-tuples', null);
        // Convert the GraphQL query to AST
        $executableDocument = null;
        $documentASTNodeAncestors = null;
        $documentObjectResolvedFieldValueReferencedFields = [];
        try {
            $graphQLQueryParsingPayload = $this->getGraphQLParserHelperService()->parseGraphQLQuery($query, $variables, $operationName);
            $executableDocument = $graphQLQueryParsingPayload->executableDocument;
            $documentObjectResolvedFieldValueReferencedFields = $graphQLQueryParsingPayload->objectResolvedFieldValueReferencedFields;
        } catch (AbstractASTNodeException $astNodeException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(new QueryFeedback($astNodeException->getFeedbackItemResolution(), $astNodeException->getAstNode()));
        } catch (AbstractASTNodeParserException $astNodeParserException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(new QueryFeedback($astNodeParserException->getFeedbackItemResolution(), $astNodeParserException->getAstNode()));
        } catch (AbstractParserException $parserException) {
            App::getFeedbackStore()->documentFeedbackStore->addError(new DocumentFeedback($parserException->getFeedbackItemResolution(), $parserException->getLocation()));
        }
        $appStateManager->override('document-object-resolved-field-value-referenced-fields', $documentObjectResolvedFieldValueReferencedFields);
        if ($executableDocument !== null) {
            /**
             * Calculate now, as it's useful also if the validation
             * of the ExecutableDocument has errors.
             */
            $documentASTNodeAncestors = $executableDocument->getDocument()->getASTNodeAncestors();
            try {
                $executableDocument->validateAndInitialize();
            } catch (AbstractASTNodeException $astNodeException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(new QueryFeedback($astNodeException->getFeedbackItemResolution(), $astNodeException->getAstNode()));
            } catch (AbstractQueryException $queryException) {
                $executableDocument = null;
                App::getFeedbackStore()->documentFeedbackStore->addError(new QueryFeedback($queryException->getFeedbackItemResolution(), $queryException->getAstNode()));
            }
        }
        $appStateManager->override('executable-document-ast', $executableDocument);
        $appStateManager->override('document-ast-node-ancestors', $documentASTNodeAncestors);
        /**
         * Set the operation type and, based on it, if mutations are supported.
         * If there's an error in `parseGraphQLQuery`, $executableDocument will be null.
         */
        if ($executableDocument === null) {
            $appStateManager->override('does-api-query-have-errors', \true);
        }
        // Generate the data, print the response to buffer, and send headers
        $engine->generateDataAndPrepareResponse();
        // Return the Response, so the client can retrieve content and headers
        return App::getResponse();
    }
}