<?php

declare (strict_types=1);
namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\Constants\ConfigurationValues;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries\ConfigurableMandatoryDirectivesForFieldsTrait;
trait ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait
{
    use ConfigurableMandatoryDirectivesForFieldsTrait;
    /**
     * @return array<class-string<RelationalTypeResolverInterface>|string> Either the class, or the constant "*" to represent _any_ class
     */
    public function getRelationalTypeResolverClassesToAttachTo() : array
    {
        return \array_values(\array_unique(\array_map(
            // The tuple has format [typeOrInterfaceTypeFieldResolverClass | "*", fieldName]
            // or [typeOrInterfaceTypeFieldResolverClass | "*", fieldName, $role]
            // or [typeOrInterfaceTypeFieldResolverClass | "*", fieldName, $capability]
            // So, in position [0], will always be the $typeOrInterfaceTypeFieldResolverClass or "*" (for any type or interface)
            function (array $entry) {
                return $entry[0];
            },
            $this->getConfigurationEntries()
        )));
    }
    /**
     * @return Directive[]
     * @param mixed $entryValue
     */
    protected abstract function getMandatoryDirectives($entryValue = null) : array;
    /**
     * @return array<string,Directive[]> Key: fieldName or "*" (for any field), Value: List of Directives
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     */
    public function getMandatoryDirectivesForFields($objectTypeResolver) : array
    {
        $mandatoryDirectivesForFields = [];
        $interfaceTypeResolvers = $objectTypeResolver->getImplementedInterfaceTypeResolvers();
        // Obtain all capabilities allowed for the current combination of typeResolver/fieldName
        foreach ($this->getFieldNames() as $fieldName) {
            // Calculate all the interfaces that define this fieldName
            $interfaceTypeResolversForField = $fieldName === ConfigurationValues::ANY ? $interfaceTypeResolvers : \array_values(\array_filter($interfaceTypeResolvers, function (InterfaceTypeResolverInterface $interfaceTypeResolver) use($fieldName) {
                return \in_array($fieldName, $interfaceTypeResolver->getFieldNamesToImplement());
            }));
            foreach ($this->getEntriesByTypeAndInterfaces($objectTypeResolver, $interfaceTypeResolversForField, $fieldName) as $entry) {
                $entryValue = $entry[2] ?? null;
                if ($this->removeFieldNameBasedOnMatchingEntryValue($entryValue)) {
                    $mandatoryDirectivesForFields[$fieldName] = $this->getMandatoryDirectives($entryValue);
                }
            }
        }
        return $mandatoryDirectivesForFields;
    }
    /**
     * @param mixed $entryValue
     */
    protected function removeFieldNameBasedOnMatchingEntryValue($entryValue = null) : bool
    {
        return \true;
    }
}
