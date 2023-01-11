<?php

declare (strict_types=1);
namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
trait ConfigurableMandatoryDirectivesForFieldsTrait
{
    /**
     * Configuration entries
     *
     * @return array<mixed[]>
     */
    protected abstract function getConfigurationEntries() : array;
    /**
     * Field names to remove
     *
     * @return string[]
     */
    protected function getFieldNames() : array
    {
        return \array_values(\array_unique(\array_map(
            // The tuple has format [typeOrInterfaceTypeFieldResolverClass, fieldName | "*"]
            // or [typeOrInterfaceTypeFieldResolverClass, fieldName | "*", $role]
            // or [typeOrInterfaceTypeFieldResolverClass, fieldName | "*", $capability]
            // So, in position [1], will always be the $fieldName or "*" (for any field)
            function (array $entry) {
                return $entry[1];
            },
            $this->getConfigurationEntries()
        )));
    }
    /**
     * Configuration entries
     *
     * @return array<mixed[]>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|\PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver
     * @param \PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface|\PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver
     * @param string $fieldName
     */
    protected final function getEntries($objectTypeOrInterfaceTypeResolver, $objectTypeOrInterfaceTypeFieldResolver, $fieldName) : array
    {
        return $this->getEntriesByTypeAndInterfaces(
            $objectTypeOrInterfaceTypeResolver,
            /**
             * Pass the list of all the interfaces implemented by the objectTypeOrInterfaceTypeFieldResolver,
             * and not only those ones containing the fieldName.
             * This is because otherwise we'd need to call `$interfaceTypeResolver->getFieldNamesToImplement()`
             * to find out the list of Interfaces containing $fieldName, however this function relies
             * on the InterfaceTypeFieldResolver once again, so we'd get a recursion.
             */
            $objectTypeOrInterfaceTypeFieldResolver->getPartiallyImplementedInterfaceTypeResolvers(),
            $fieldName
        );
    }
    /**
     * Configuration entries
     *
     * @param InterfaceTypeResolverInterface[] $interfaceTypeResolvers
     *
     * @return array<mixed[]>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|\PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver
     * @param string $fieldName
     */
    protected final function getEntriesByTypeAndInterfaces($objectTypeOrInterfaceTypeResolver, $interfaceTypeResolvers, $fieldName) : array
    {
        $entryList = $this->getConfigurationEntries();
        if ($entryList === []) {
            return [];
        }
        return $this->getMatchingEntries($entryList, $objectTypeOrInterfaceTypeResolver, $interfaceTypeResolvers, $fieldName);
    }
    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     *
     * @param InterfaceTypeResolverInterface[] $interfaceTypeResolvers
     *
     * @return array<mixed[]>
     * @param array<mixed[]> $entryList
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface|\PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver
     * @param string $fieldName
     */
    protected final function getMatchingEntries($entryList, $objectTypeOrInterfaceTypeResolver, $interfaceTypeResolvers, $fieldName) : array
    {
        $objectTypeOrInterfaceTypeResolverClass = \get_class($objectTypeOrInterfaceTypeResolver);
        $interfaceTypeResolverClasses = \array_map(\Closure::fromCallable('get_class'), $interfaceTypeResolvers);
        return \array_values(\array_filter($entryList, function (array $entry) use($objectTypeOrInterfaceTypeResolverClass, $interfaceTypeResolverClasses, $fieldName) {
            return ($entry[0] === $objectTypeOrInterfaceTypeResolverClass || \in_array($entry[0], $interfaceTypeResolverClasses) || $entry[0] === ConfigurationValues::ANY) && ($entry[1] === $fieldName || $entry[1] === ConfigurationValues::ANY);
        }));
    }
}
