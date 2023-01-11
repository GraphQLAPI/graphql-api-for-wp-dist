<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\ConditionalOnModule\CacheControl\Overrides\Services;

use GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface;
use PoP\CacheControl\Managers\CacheControlManager as UpstreamCacheControlManager;
use PoP\Root\Services\BasicServiceTrait;
class CacheControlManager extends UpstreamCacheControlManager
{
    use BasicServiceTrait;
    /**
     * @var array<mixed[]>|null
     */
    protected $overriddenFieldEntries;
    /**
     * @var \GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface|null
     */
    private $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    /**
     * @param \GraphQLByPoP\GraphQLServer\IFTTT\MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface $mandatoryDirectivesForFieldsRootTypeEntryDuplicator
     */
    public final function setMandatoryDirectivesForFieldsRootTypeEntryDuplicator($mandatoryDirectivesForFieldsRootTypeEntryDuplicator) : void
    {
        $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $mandatoryDirectivesForFieldsRootTypeEntryDuplicator;
    }
    protected final function getMandatoryDirectivesForFieldsRootTypeEntryDuplicator() : MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface
    {
        /** @var MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface */
        return $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator = $this->mandatoryDirectivesForFieldsRootTypeEntryDuplicator ?? $this->instanceManager->getInstance(MandatoryDirectivesForFieldsRootTypeEntryDuplicatorInterface::class);
    }
    /**
     * @param array<mixed[]> $fieldEntries
     */
    public function addEntriesForFields($fieldEntries) : void
    {
        parent::addEntriesForFields($fieldEntries);
        // Make sure to reset getting the entries
        $this->overriddenFieldEntries = null;
    }
    /**
     * Add additional entries: whenever Root is used,
     * duplicate it also for both QueryRoot and MutationRoot,
     * so that the user needs to set the configuration only once.
     *
     * Add this logic when retrieving the entries because by then
     * the container is compiled and we can access the RootObjectTypeResolver
     * instance. In contrast, `addEntriesForFields` can be called
     * within a CompilerPass, so the instances would not yet be available.
     *
     * @return array<mixed[]>
     */
    public function getEntriesForFields() : array
    {
        if ($this->overriddenFieldEntries !== null) {
            return $this->overriddenFieldEntries;
        }
        $this->overriddenFieldEntries = $this->getMandatoryDirectivesForFieldsRootTypeEntryDuplicator()->maybeAppendAdditionalRootEntriesForFields(parent::getEntriesForFields());
        return $this->overriddenFieldEntries;
    }
}
