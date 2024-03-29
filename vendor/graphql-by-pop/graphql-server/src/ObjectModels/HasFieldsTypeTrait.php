<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Module;
use GraphQLByPoP\GraphQLServer\ModuleConfiguration;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaDefinitionServiceInterface;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\SchemaDefinition;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
trait HasFieldsTypeTrait
{
    /**
     * @var Field[]
     */
    protected $fields;
    protected static function getGraphQLSchemaDefinitionService() : GraphQLSchemaDefinitionServiceInterface
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaDefinitionServiceInterface */
        return $instanceManager->getInstance(GraphQLSchemaDefinitionServiceInterface::class);
    }
    /**
     * @param array<string,mixed> $fullSchemaDefinition
     * @param string[] $schemaDefinitionPath
     */
    protected function initFields(&$fullSchemaDefinition, $schemaDefinitionPath) : void
    {
        /**
         * Iterate to the definition of the fields in the schema,
         * and create an object for each of them
         */
        $this->fields = SchemaDefinitionHelpers::createFieldsFromPath($fullSchemaDefinition, \array_merge($schemaDefinitionPath, [SchemaDefinition::FIELDS]));
        $globalFields = [];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->exposeGlobalFieldsInGraphQLSchema()) {
            /**
             * Display the Global Fields either under all types,
             * or only under the Root type
             */
            if (!$moduleConfiguration->exposeGlobalFieldsInRootTypeOnlyInGraphQLSchema() || $this->getNamespacedName() === $this->getGraphQLSchemaDefinitionService()->getSchemaQueryRootObjectTypeResolver()->getNamespacedTypeName()) {
                /**
                 * Global fields have already been initialized,
                 * simply get the reference to the existing objects
                 * from the registryMap
                 */
                $globalFields = SchemaDefinitionHelpers::getFieldsFromPath($fullSchemaDefinition, [SchemaDefinition::GLOBAL_FIELDS]);
            }
        }
        // Maybe sort fields and connections all together
        if ($moduleConfiguration->sortGraphQLSchemaAlphabetically()) {
            if ($moduleConfiguration->sortGlobalFieldsAfterNormalFieldsInGraphQLSchema()) {
                /**
                 * Sort them separately, then merge them
                 */
                \uasort($this->fields, function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $a, \GraphQLByPoP\GraphQLServer\ObjectModels\Field $b) {
                    return $a->getName() <=> $b->getName();
                });
                \uasort($globalFields, function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $a, \GraphQLByPoP\GraphQLServer\ObjectModels\Field $b) {
                    return $a->getName() <=> $b->getName();
                });
                $this->fields = \array_merge($this->fields, $globalFields);
            } else {
                /**
                 * Merge them, then sort them together
                 */
                $this->fields = \array_merge($this->fields, $globalFields);
                \uasort($this->fields, function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $a, \GraphQLByPoP\GraphQLServer\ObjectModels\Field $b) {
                    return $a->getName() <=> $b->getName();
                });
            }
        } else {
            $this->fields = \array_merge($this->fields, $globalFields);
        }
    }
    public abstract function getNamespacedName() : string;
    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return Field[]
     * @param bool $includeDeprecated
     */
    public function getFields($includeDeprecated = \false, $includeGlobal = \true) : array
    {
        $fields = $this->fields;
        if (!$includeDeprecated) {
            $fields = \array_filter($fields, function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $field) {
                return !$field->isDeprecated();
            });
        }
        if (!$includeGlobal) {
            $fields = \array_filter($fields, function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $field) {
                return !$field->getExtensions()->isGlobal();
            });
        }
        return $fields;
    }
    /**
     * @param bool $includeGlobal Custom parameter by this GraphQL Server (i.e. it is not present in the GraphQL spec)
     * @return string[]
     * @param bool $includeDeprecated
     */
    public function getFieldIDs($includeDeprecated = \false, $includeGlobal = \true) : array
    {
        return \array_map(function (\GraphQLByPoP\GraphQLServer\ObjectModels\Field $field) {
            return $field->getID();
        }, $this->getFields($includeDeprecated, $includeGlobal));
    }
}
