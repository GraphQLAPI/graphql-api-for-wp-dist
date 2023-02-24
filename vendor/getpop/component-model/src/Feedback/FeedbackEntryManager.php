<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\DatabasesOutputModes;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\ObjectResolutionFeedbackInterface;
use PoP\ComponentModel\Feedback\QueryFeedbackInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Response\DatabaseEntryManagerInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;
class FeedbackEntryManager implements \PoP\ComponentModel\Feedback\FeedbackEntryManagerInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\Response\DatabaseEntryManagerInterface|null
     */
    private $databaseEntryManager;
    /**
     * @param \PoP\ComponentModel\Response\DatabaseEntryManagerInterface $databaseEntryManager
     */
    public final function setDatabaseEntryManager($databaseEntryManager) : void
    {
        $this->databaseEntryManager = $databaseEntryManager;
    }
    protected final function getDatabaseEntryManager() : DatabaseEntryManagerInterface
    {
        /** @var DatabaseEntryManagerInterface */
        return $this->databaseEntryManager = $this->databaseEntryManager ?? $this->instanceManager->getInstance(DatabaseEntryManagerInterface::class);
    }
    /**
     * Add the feedback (errors, warnings, deprecations, notices, etc)
     * into the output.
     *
     * @param array<string,mixed> $data
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     * @return array<string,mixed>
     */
    public function combineAndAddFeedbackEntries($data, $schemaFeedbackEntries, $objectFeedbackEntries) : array
    {
        $data[Response::GENERAL_FEEDBACK] = [];
        $data[Response::DOCUMENT_FEEDBACK] = [];
        $data[Response::SCHEMA_FEEDBACK] = [];
        $data[Response::OBJECT_FEEDBACK] = [];
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $enabledFeedbackCategoryExtensions = $moduleConfiguration->getEnabledFeedbackCategoryExtensions();
        $sendFeedbackWarnings = \in_array(\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING, $enabledFeedbackCategoryExtensions);
        $sendFeedbackDeprecations = \in_array(\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION, $enabledFeedbackCategoryExtensions);
        $sendFeedbackNotices = \in_array(\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE, $enabledFeedbackCategoryExtensions);
        $sendFeedbackSuggestions = \in_array(\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION, $enabledFeedbackCategoryExtensions);
        $sendFeedbackLogs = \in_array(\PoP\ComponentModel\Feedback\FeedbackCategories::LOG, $enabledFeedbackCategoryExtensions);
        // Errors
        $generalFeedbackStore = App::getFeedbackStore()->generalFeedbackStore;
        if ($generalErrors = $generalFeedbackStore->getErrors()) {
            $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR] = $this->getGeneralFeedbackEntriesForOutput($generalErrors);
        }
        $documentFeedbackStore = App::getFeedbackStore()->documentFeedbackStore;
        if ($documentErrors = $documentFeedbackStore->getErrors()) {
            $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR] = $this->getDocumentFeedbackEntriesForOutput($documentErrors);
        }
        $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::ERROR, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR]);
        $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::ERROR, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR]);
        // Warnings
        if ($sendFeedbackWarnings) {
            if ($generalWarnings = $generalFeedbackStore->getWarnings()) {
                $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING] = $this->getGeneralFeedbackEntriesForOutput($generalWarnings);
            }
            if ($documentWarnings = $documentFeedbackStore->getWarnings()) {
                $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING] = $this->getDocumentFeedbackEntriesForOutput($documentWarnings);
            }
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::WARNING, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING]);
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::WARNING, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING]);
        }
        // Deprecations
        if ($sendFeedbackDeprecations) {
            if ($generalDeprecations = $generalFeedbackStore->getDeprecations()) {
                $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION] = $this->getGeneralFeedbackEntriesForOutput($generalDeprecations);
            }
            if ($documentDeprecations = $documentFeedbackStore->getDeprecations()) {
                $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION] = $this->getDocumentFeedbackEntriesForOutput($documentDeprecations);
            }
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION]);
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION]);
        }
        // Notices
        if ($sendFeedbackNotices) {
            if ($generalNotices = $generalFeedbackStore->getNotices()) {
                $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE] = $this->getGeneralFeedbackEntriesForOutput($generalNotices);
            }
            if ($documentNotices = $documentFeedbackStore->getNotices()) {
                $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE] = $this->getDocumentFeedbackEntriesForOutput($documentNotices);
            }
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE]);
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE]);
        }
        // Suggestions
        if ($sendFeedbackSuggestions) {
            if ($generalSuggestions = $generalFeedbackStore->getSuggestions()) {
                $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION] = $this->getGeneralFeedbackEntriesForOutput($generalSuggestions);
            }
            if ($documentSuggestions = $documentFeedbackStore->getSuggestions()) {
                $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION] = $this->getDocumentFeedbackEntriesForOutput($documentSuggestions);
            }
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION]);
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION]);
        }
        // Logs
        if ($sendFeedbackLogs) {
            if ($generalLogs = $generalFeedbackStore->getLogs()) {
                $data[Response::GENERAL_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::LOG] = $this->getGeneralFeedbackEntriesForOutput($generalLogs);
            }
            if ($documentLogs = $documentFeedbackStore->getLogs()) {
                $data[Response::DOCUMENT_FEEDBACK][\PoP\ComponentModel\Feedback\FeedbackCategories::LOG] = $this->getDocumentFeedbackEntriesForOutput($documentLogs);
            }
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::SCHEMA_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::LOG, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::LOG]);
            $this->maybeCombineAndAddObjectOrSchemaEntries($data[Response::OBJECT_FEEDBACK], \PoP\ComponentModel\Feedback\FeedbackCategories::LOG, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::LOG]);
        }
        return $data;
    }
    /**
     * @param array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>> $entries
     * @param array<string,mixed> $ret
     * @param string $name
     */
    protected function maybeCombineAndAddObjectOrSchemaEntries(&$ret, $name, $entries) : void
    {
        if ($entries === []) {
            return;
        }
        $dboutputmode = App::getState('dboutputmode');
        // Combine all the databases or send them separate
        if ($dboutputmode === DatabasesOutputModes::SPLITBYDATABASES) {
            $ret[$name] = $entries;
            return;
        }
        if ($dboutputmode === DatabasesOutputModes::COMBINED) {
            // Filter to make sure there are entries
            if ($entries = \array_filter($entries)) {
                /** @var array<string,SplObjectStorage<FieldInterface,array<string,mixed>>> */
                $combined_databases = [];
                foreach ($entries as $database_name => $database) {
                    foreach ($database as $typeOutputKey => $fieldEntries) {
                        /** @var SplObjectStorage<FieldInterface,array<string,mixed>> */
                        $combinedDatabasesType = $combined_databases[$typeOutputKey] ?? new SplObjectStorage();
                        /** @var FieldInterface $field */
                        foreach ($fieldEntries as $field) {
                            /** @var array<string,mixed> */
                            $combinedDatabasesTypeField = $combinedDatabasesType[$field] ?? [];
                            /** @var array<string,mixed> */
                            $entries = $fieldEntries[$field];
                            $combinedDatabasesTypeField = \array_merge($combinedDatabasesTypeField, $entries);
                            $combinedDatabasesType[$field] = $combinedDatabasesTypeField;
                        }
                        $combined_databases[$typeOutputKey] = $combinedDatabasesType;
                    }
                }
                $ret[$name] = $combined_databases;
            }
        }
    }
    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> $iterationEntries
     * @param array<string,array<string,SplObjectStorage<FieldInterface,mixed>>> $destination
     */
    protected function addFeedbackEntries($iterationEntries, &$destination) : void
    {
        if ($iterationEntries->count() === 0) {
            return;
        }
        $databaseEntryManager = $this->getDatabaseEntryManager();
        /** @var RelationalTypeResolverInterface $iterationRelationalTypeResolver */
        foreach ($iterationEntries as $iterationRelationalTypeResolver) {
            $typeOutputKey = $iterationRelationalTypeResolver->getTypeOutputKey();
            $entries = $iterationEntries[$iterationRelationalTypeResolver];
            $dbNameEntries = $databaseEntryManager->moveEntriesWithoutIDUnderDBName($entries, $iterationRelationalTypeResolver);
            foreach ($dbNameEntries as $dbName => $entries) {
                /** @var SplObjectStorage<FieldInterface,mixed> */
                $destinationSplObjectStorage = $destination[$dbName][$typeOutputKey] ?? new SplObjectStorage();
                $destinationSplObjectStorage->addAll($entries);
                $destination[$dbName][$typeOutputKey] = $destinationSplObjectStorage;
            }
        }
    }
    /**
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $objectFeedbackEntries
     * @param \PoP\ComponentModel\Feedback\ObjectResolutionFeedbackStore $objectResolutionFeedbackStore
     */
    public function transferObjectFeedback($idObjects, $objectResolutionFeedbackStore, &$objectFeedbackEntries) : void
    {
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectErrors = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getErrors() as $objectFeedbackError) {
            $this->transferObjectFeedbackEntries($objectFeedbackError, $iterationObjectErrors);
        }
        $this->addFeedbackEntries($iterationObjectErrors, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectWarnings = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getWarnings() as $objectFeedbackWarning) {
            $this->transferObjectFeedbackEntries($objectFeedbackWarning, $iterationObjectWarnings);
        }
        $this->addFeedbackEntries($iterationObjectWarnings, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectDeprecations = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getDeprecations() as $objectFeedbackDeprecation) {
            $this->transferObjectFeedbackEntries($objectFeedbackDeprecation, $iterationObjectDeprecations);
        }
        $this->addFeedbackEntries($iterationObjectDeprecations, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectNotices = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getNotices() as $objectFeedbackNotice) {
            $this->transferObjectFeedbackEntries($objectFeedbackNotice, $iterationObjectNotices);
        }
        $this->addFeedbackEntries($iterationObjectNotices, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectSuggestions = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getSuggestions() as $objectFeedbackSuggestion) {
            $this->transferObjectFeedbackEntries($objectFeedbackSuggestion, $iterationObjectSuggestions);
        }
        $this->addFeedbackEntries($iterationObjectSuggestions, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationObjectLogs = new SplObjectStorage();
        foreach ($objectResolutionFeedbackStore->getLogs() as $objectFeedbackLog) {
            $this->transferObjectFeedbackEntries($objectFeedbackLog, $iterationObjectLogs);
        }
        $this->addFeedbackEntries($iterationObjectLogs, $objectFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::LOG]);
    }
    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> $iterationObjectFeedbackEntries
     */
    private function transferObjectFeedbackEntries(ObjectResolutionFeedbackInterface $objectFeedback, SplObjectStorage $iterationObjectFeedbackEntries) : void
    {
        $relationalTypeResolver = $objectFeedback->getRelationalTypeResolver();
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
            $targetIterationObjectFeedbackEntries = new SplObjectStorage();
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            $targetTypeOutputKeyObjectTypeResolvers = $unionTypeResolver->getTargetTypeOutputKeyObjectTypeResolvers();
            /** @var SplObjectStorage<RelationalTypeResolverInterface,array<string|int,EngineIterationFieldSet>> */
            $targetObjectTypeResolverIDFieldSet = new SplObjectStorage();
            foreach ($objectFeedback->getIDFieldSet() as $id => $fieldSet) {
                /**
                 * If the type data resolver is union, the typeOutputKey where the value is stored
                 * is contained in the ID itself, with format typeOutputKey/ID.
                 * Remove this information, and get purely the ID
                 *
                 * @var string $id
                 */
                list($objectTypeOutputKey, $id) = UnionTypeHelpers::extractObjectTypeAndID($id);
                $idTargetObjectTypeResolver = $targetTypeOutputKeyObjectTypeResolvers[$objectTypeOutputKey] ?? $relationalTypeResolver;
                $idTargetObjectTypeResolverIDFieldSet = $targetObjectTypeResolverIDFieldSet[$idTargetObjectTypeResolver] ?? [];
                $idTargetObjectTypeResolverIDFieldSet[$id] = $fieldSet;
                $targetObjectTypeResolverIDFieldSet[$idTargetObjectTypeResolver] = $idTargetObjectTypeResolverIDFieldSet;
                /** @var SplObjectStorage<FieldInterface,mixed> */
                $idTargetObjectFeedbackEntries = $targetIterationObjectFeedbackEntries[$idTargetObjectTypeResolver] ?? new SplObjectStorage();
                foreach ($fieldSet->fields as $field) {
                    if (!$iterationObjectFeedbackEntries->contains($relationalTypeResolver) || !$iterationObjectFeedbackEntries[$relationalTypeResolver]->contains($field)) {
                        continue;
                    }
                    $idTargetObjectFeedbackEntries[$field] = $iterationObjectFeedbackEntries[$relationalTypeResolver][$field];
                }
                $targetIterationObjectFeedbackEntries[$idTargetObjectTypeResolver] = $idTargetObjectFeedbackEntries;
            }
            /** @var RelationalTypeResolverInterface $targetObjectTypeResolver */
            foreach ($targetIterationObjectFeedbackEntries as $targetObjectTypeResolver) {
                $this->doTransferObjectFeedbackEntries($objectFeedback, $iterationObjectFeedbackEntries, $targetObjectTypeResolver, $targetObjectTypeResolverIDFieldSet[$targetObjectTypeResolver]);
            }
            return;
        }
        $this->doTransferObjectFeedbackEntries($objectFeedback, $iterationObjectFeedbackEntries, $objectFeedback->getRelationalTypeResolver(), $objectFeedback->getIDFieldSet());
    }
    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> $iterationObjectFeedbackEntries
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     */
    private function doTransferObjectFeedbackEntries(ObjectResolutionFeedbackInterface $objectFeedback, SplObjectStorage $iterationObjectFeedbackEntries, RelationalTypeResolverInterface $relationalTypeResolver, array $idFieldSet) : void
    {
        $entry = $this->getObjectOrSchemaFeedbackCommonEntry($objectFeedback);
        $fieldIDs = MethodHelpers::orderIDsByDirectFields($idFieldSet);
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $objectFeedbackEntries = $iterationObjectFeedbackEntries[$relationalTypeResolver] ?? new SplObjectStorage();
        /** @var FieldInterface $field */
        foreach ($fieldIDs as $field) {
            $fieldEntry = $this->addFieldToObjectOrSchemaFeedbackEntry($entry, $field);
            /** @var array<string|int> */
            $ids = $fieldIDs[$field];
            $fieldEntry[\PoP\ComponentModel\Feedback\Tokens::IDS] = $ids;
            $fieldObjectFeedbackEntries = $objectFeedbackEntries[$field] ?? [];
            $fieldObjectFeedbackEntries[] = $fieldEntry;
            $objectFeedbackEntries[$field] = $fieldObjectFeedbackEntries;
        }
        $iterationObjectFeedbackEntries[$relationalTypeResolver] = $objectFeedbackEntries;
    }
    /**
     * @param array<string,array<string,array<string,SplObjectStorage<FieldInterface,array<string,mixed>>>>> $schemaFeedbackEntries
     * @param \PoP\ComponentModel\Feedback\SchemaFeedbackStore $schemaFeedbackStore
     */
    public function transferSchemaFeedback($schemaFeedbackStore, &$schemaFeedbackEntries) : void
    {
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaErrors = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getErrors() as $schemaFeedbackError) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackError, $iterationSchemaErrors);
        }
        $this->addFeedbackEntries($iterationSchemaErrors, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::ERROR]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaWarnings = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getWarnings() as $schemaFeedbackWarning) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackWarning, $iterationSchemaWarnings);
        }
        $this->addFeedbackEntries($iterationSchemaWarnings, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::WARNING]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaDeprecations = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getDeprecations() as $schemaFeedbackDeprecation) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackDeprecation, $iterationSchemaDeprecations);
        }
        $this->addFeedbackEntries($iterationSchemaDeprecations, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::DEPRECATION]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaNotices = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getNotices() as $schemaFeedbackNotice) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackNotice, $iterationSchemaNotices);
        }
        $this->addFeedbackEntries($iterationSchemaNotices, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::NOTICE]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaSuggestions = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getSuggestions() as $schemaFeedbackSuggestion) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackSuggestion, $iterationSchemaSuggestions);
        }
        $this->addFeedbackEntries($iterationSchemaSuggestions, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::SUGGESTION]);
        /** @var SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> */
        $iterationSchemaLogs = new SplObjectStorage();
        foreach ($schemaFeedbackStore->getLogs() as $schemaFeedbackLog) {
            $this->transferSchemaFeedbackEntries($schemaFeedbackLog, $iterationSchemaLogs);
        }
        $this->addFeedbackEntries($iterationSchemaLogs, $schemaFeedbackEntries[\PoP\ComponentModel\Feedback\FeedbackCategories::LOG]);
    }
    /**
     * @param SplObjectStorage<RelationalTypeResolverInterface,SplObjectStorage<FieldInterface,mixed>> $iterationSchemaFeedbackEntries
     */
    private function transferSchemaFeedbackEntries(\PoP\ComponentModel\Feedback\SchemaFeedbackInterface $schemaFeedback, SplObjectStorage $iterationSchemaFeedbackEntries) : void
    {
        $entry = $this->getObjectOrSchemaFeedbackCommonEntry($schemaFeedback);
        /** @var SplObjectStorage<FieldInterface,mixed> */
        $schemaFeedbackEntries = $iterationSchemaFeedbackEntries[$schemaFeedback->getRelationalTypeResolver()] ?? new SplObjectStorage();
        foreach ($schemaFeedback->getFields() as $field) {
            $fieldSchemaFeedbackEntries = $schemaFeedbackEntries[$field] ?? [];
            $fieldEntry = $this->addFieldToObjectOrSchemaFeedbackEntry($entry, $field);
            $fieldSchemaFeedbackEntries[] = $fieldEntry;
            $schemaFeedbackEntries[$field] = $fieldSchemaFeedbackEntries;
        }
        $iterationSchemaFeedbackEntries[$schemaFeedback->getRelationalTypeResolver()] = $schemaFeedbackEntries;
    }
    /**
     * @return array<string,mixed>
     * @param \PoP\ComponentModel\Feedback\ObjectResolutionFeedbackInterface|\PoP\ComponentModel\Feedback\SchemaFeedbackInterface $objectOrSchemaFeedback
     */
    private function getObjectOrSchemaFeedbackCommonEntry($objectOrSchemaFeedback) : array
    {
        return $this->formatObjectOrSchemaFeedbackCommonEntry($objectOrSchemaFeedback->getAstNode(), $objectOrSchemaFeedback->getLocation(), $objectOrSchemaFeedback->getExtensions(), $objectOrSchemaFeedback->getFeedbackItemResolution(), []);
    }
    /**
     * @param Location|null $location If `null` use the Location from the astNode
     * @param array<string,mixed> $extensions
     * @param array<string|int> $ids
     * @return array<string,mixed>
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\Root\Feedback\FeedbackItemResolution $feedbackItemResolution
     */
    public function formatObjectOrSchemaFeedbackCommonEntry($astNode, $location, $extensions, $feedbackItemResolution, $ids) : array
    {
        /**
         * If `null` use the Location from the astNode
         */
        if ($location === null) {
            $location = $astNode->getLocation();
        }
        /**
         * If it is a RuntimeLocation and it has a static AST node,
         * then use that Location
         */
        if ($location instanceof RuntimeLocation) {
            /** @var RuntimeLocation $location */
            $location = ($getStaticASTNode = $location->getStaticASTNode()) ? $getStaticASTNode->getLocation() : null;
            /** @var Location|null $location */
        }
        $locations = [];
        if ($location !== null && !$location instanceof RuntimeLocation) {
            $locations[] = $location->toArray();
        }
        $extensions = $this->addFeedbackEntryExtensions($extensions, $feedbackItemResolution);
        $entry = [\PoP\ComponentModel\Feedback\Tokens::MESSAGE => $feedbackItemResolution->getMessage(), \PoP\ComponentModel\Feedback\Tokens::PATH => $this->getASTNodePath($astNode), \PoP\ComponentModel\Feedback\Tokens::IDS => $ids, \PoP\ComponentModel\Feedback\Tokens::LOCATIONS => $locations, \PoP\ComponentModel\Feedback\Tokens::EXTENSIONS => $extensions];
        /**
         * Add the causes of the error, if any.
         *
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        $this->addObjectOrSchemaFeedbackCausesToCommonEntry($entry, $feedbackItemResolution);
        return $entry;
    }
    /**
     * @param array<string,mixed> $entry
     *
     * @see https://github.com/graphql/graphql-spec/issues/893
     */
    private function addObjectOrSchemaFeedbackCausesToCommonEntry(array &$entry, FeedbackItemResolution $feedbackItemResolution) : void
    {
        if ($feedbackItemResolution->getCauses() === []) {
            return;
        }
        $entry[\PoP\ComponentModel\Feedback\Tokens::CAUSES] = [];
        foreach ($feedbackItemResolution->getCauses() as $causeFeedbackItemResolution) {
            $causeSubentry = [\PoP\ComponentModel\Feedback\Tokens::MESSAGE => $causeFeedbackItemResolution->getMessage()];
            /**
             * The cause may itself have its own underlying causes
             */
            $this->addObjectOrSchemaFeedbackCausesToCommonEntry($causeSubentry, $causeFeedbackItemResolution);
            $entry[\PoP\ComponentModel\Feedback\Tokens::CAUSES][] = $causeSubentry;
        }
    }
    /**
     * Re-create the path to the AST node.
     *
     * Skip if the AST node was created on runtime.
     * Eg: _id6x7_title7x7__isTypeOrImplementsAll_CustomPost_: _isTypeOrImplementsAll(typesOrInterfaces: [\"CustomPost\"])
     *
     * @return string[]
     */
    private function getASTNodePath(AstInterface $astNode) : array
    {
        $location = $astNode->getLocation();
        if ($location instanceof RuntimeLocation) {
            /** @var RuntimeLocation $location */
            $astNode = $location->getStaticASTNode();
        }
        $astNodePath = [];
        /** @var SplObjectStorage<AstInterface,AstInterface> */
        $documentASTNodeAncestors = App::getState('document-ast-node-ancestors');
        while ($astNode !== null) {
            $astNodePath[] = $astNode->asASTNodeString();
            // Move to the ancestor AST node
            $astNode = $documentASTNodeAncestors[$astNode] ?? null;
            $location = ($astNode2 = $astNode) ? $astNode2->getLocation() : null;
            if ($location instanceof RuntimeLocation) {
                /** @var RuntimeLocation $location */
                $astNode = $location->getStaticASTNode();
            }
        }
        return $astNodePath;
    }
    /**
     * @return array<string,mixed>
     */
    private function getFeedbackEntryExtensions(\PoP\ComponentModel\Feedback\FeedbackInterface $feedback) : array
    {
        return $this->addFeedbackEntryExtensions($feedback->getExtensions(), $feedback->getFeedbackItemResolution());
    }
    /**
     * @param array<string,mixed> $extensions
     * @return array<string,mixed>
     */
    private function addFeedbackEntryExtensions(array $extensions, FeedbackItemResolution $feedbackItemResolution) : array
    {
        $extensions['code'] = $feedbackItemResolution->getNamespacedCode();
        $specifiedByURL = $feedbackItemResolution->getSpecifiedByURL();
        if ($specifiedByURL !== null) {
            $extensions['specifiedBy'] = $specifiedByURL;
        }
        return $extensions;
    }
    /**
     * Place the Field under a different key if the AST
     * node was created on runtime.
     *
     * @param array<string,mixed> $entry
     * @return array<string,mixed>
     */
    private function addFieldToObjectOrSchemaFeedbackEntry(array $entry, FieldInterface $field) : array
    {
        $key = \PoP\ComponentModel\Feedback\Tokens::FIELD;
        $location = $field->getLocation();
        if ($location instanceof RuntimeLocation && $location->getStaticASTNode() === null) {
            $key = \PoP\ComponentModel\Feedback\Tokens::DYNAMIC_FIELD;
        }
        return \array_merge($entry, [$key => $field->asASTNodeString()]);
    }
    /**
     * @param GeneralFeedbackInterface[] $generalFeedbackEntries
     * @return array<array<string,mixed>>
     */
    protected function getGeneralFeedbackEntriesForOutput($generalFeedbackEntries) : array
    {
        $output = [];
        foreach ($generalFeedbackEntries as $generalFeedbackEntry) {
            $output[] = [\PoP\ComponentModel\Feedback\Tokens::MESSAGE => $generalFeedbackEntry->getFeedbackItemResolution()->getMessage(), \PoP\ComponentModel\Feedback\Tokens::EXTENSIONS => $this->getFeedbackEntryExtensions($generalFeedbackEntry)];
        }
        return $output;
    }
    /**
     * @param DocumentFeedbackInterface[] $documentFeedbackEntries
     * @return array<array<string,mixed>>
     */
    protected function getDocumentFeedbackEntriesForOutput($documentFeedbackEntries) : array
    {
        $output = [];
        foreach ($documentFeedbackEntries as $documentFeedbackEntry) {
            $locations = [];
            $location = $documentFeedbackEntry->getLocation();
            if (!$location instanceof RuntimeLocation) {
                $locations[] = $location->toArray();
            }
            $entry = [\PoP\ComponentModel\Feedback\Tokens::MESSAGE => $documentFeedbackEntry->getFeedbackItemResolution()->getMessage(), \PoP\ComponentModel\Feedback\Tokens::LOCATIONS => $locations, \PoP\ComponentModel\Feedback\Tokens::EXTENSIONS => $this->getFeedbackEntryExtensions($documentFeedbackEntry)];
            /**
             * If also passing the AST node to the DocumentFeedback,
             * then print the path
             */
            if ($documentFeedbackEntry instanceof QueryFeedbackInterface) {
                /** @var QueryFeedbackInterface */
                $queryFeedbackEntry = $documentFeedbackEntry;
                $entry[\PoP\ComponentModel\Feedback\Tokens::PATH] = $this->getASTNodePath($queryFeedbackEntry->getAstNode());
            }
            $output[] = $entry;
        }
        return $output;
    }
}
