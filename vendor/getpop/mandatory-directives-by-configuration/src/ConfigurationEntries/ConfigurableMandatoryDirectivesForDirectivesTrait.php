<?php

declare (strict_types=1);
namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\Root\Instances\InstanceManagerInterface;
trait ConfigurableMandatoryDirectivesForDirectivesTrait
{
    protected abstract function getInstanceManager() : InstanceManagerInterface;
    /**
     * Configuration entries
     *
     * @return array<mixed[]>
     */
    protected abstract function getConfigurationEntries() : array;
    /**
     * Configuration entries
     *
     * @return array<mixed[]>
     */
    protected final function getEntries() : array
    {
        $entryList = $this->getConfigurationEntries();
        if ($entryList === []) {
            return [];
        }
        $requiredEntryValue = $this->getRequiredEntryValue();
        return $this->getMatchingEntries($entryList, $requiredEntryValue);
    }
    /**
     * The value in the 2nd element from the entry
     */
    protected function getRequiredEntryValue() : ?string
    {
        return null;
    }
    /**
     * Affected directives
     *
     * @return FieldDirectiveResolverInterface[]
     */
    protected final function getFieldDirectiveResolvers() : array
    {
        return \array_map(function (string $directiveResolverClass) : FieldDirectiveResolverInterface {
            /** @var FieldDirectiveResolverInterface */
            return $this->getInstanceManager()->getInstance($directiveResolverClass);
        }, $this->getFieldDirectiveResolverClasses());
    }
    /**
     * Remove directiveName "translate" if the user is not logged in
     *
     * @return array<class-string<FieldDirectiveResolverInterface>>
     */
    protected function getFieldDirectiveResolverClasses() : array
    {
        // Obtain all entries for the current combination of typeResolver/fieldName
        return \array_values(\array_unique(\array_map(function (array $entry) {
            return $entry[0];
        }, $this->getEntries())));
    }
    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     *
     * @return array<mixed[]>
     * @param array<mixed[]> $entryList
     * @param string|null $value
     */
    protected final function getMatchingEntries($entryList, $value) : array
    {
        if ($value !== null) {
            return \array_values(\array_filter($entryList, function (array $entry) use($value) {
                return ($entry[1] ?? null) === $value;
            }));
        }
        return $entryList;
    }
}
