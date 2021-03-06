<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

use PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups;
use Exception;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\UnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade;
use PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade;
abstract class AbstractUnionTypeResolver extends \PoP\ComponentModel\TypeResolvers\AbstractTypeResolver implements \PoP\ComponentModel\TypeResolvers\UnionTypeResolverInterface
{
    /**
     * @var TypeResolverPickerInterface[]
     */
    protected $typeResolverPickers = null;
    /**
     * This is a Union Type
     *
     * @return bool
     */
    public function isUnionType() : bool
    {
        return \true;
    }
    public final function getTypeOutputName() : string
    {
        return \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::getUnionTypeCollectionName(parent::getTypeOutputName());
    }
    public function getSchemaTypeInterfaceClass() : ?string
    {
        return null;
    }
    /**
     * Remove the type from the ID to resolve the objects through `getObjects` (check parent class)
     *
     * @param array $ids_data_fields
     * @return void
     */
    protected function getIDsToQuery(array $ids_data_fields)
    {
        $ids = parent::getIDsToQuery($ids_data_fields);
        // Each ID contains the type (added in function `getID`). Remove it
        return \array_map([\PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::class, 'extractDBObjectID'], $ids);
    }
    public function getQualifiedDBObjectIDOrIDs($dbObjectIDOrIDs)
    {
        $dbObjectIDs = \is_array($dbObjectIDOrIDs) ? $dbObjectIDOrIDs : [$dbObjectIDOrIDs];
        $resultItemIDTargetTypeResolvers = $this->getResultItemIDTargetTypeResolvers($dbObjectIDs);
        $typeDBObjectIDOrIDs = [];
        foreach ($dbObjectIDs as $resultItemID) {
            // Make sure there is a resolver for this resultItem. If there is none, return the same ID
            $targetTypeResolver = $resultItemIDTargetTypeResolvers[$resultItemID];
            if (!\is_null($targetTypeResolver)) {
                $typeDBObjectIDOrIDs[] = \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::getDBObjectComposedTypeAndID($targetTypeResolver, $resultItemID);
            } else {
                $typeDBObjectIDOrIDs[] = $resultItemID;
            }
        }
        if (!\is_array($dbObjectIDOrIDs)) {
            return $typeDBObjectIDOrIDs[0];
        }
        return $typeDBObjectIDOrIDs;
    }
    public function qualifyDBObjectIDsToRemoveFromErrors() : bool
    {
        return \true;
    }
    public function getResultItemIDTargetTypeResolvers(array $ids) : array
    {
        return $this->recursiveGetResultItemIDTargetTypeResolvers($this, $ids);
    }
    private function recursiveGetResultItemIDTargetTypeResolvers(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, array $ids) : array
    {
        if (!$ids) {
            return [];
        }
        $resultItemIDTargetTypeResolvers = [];
        $isUnionTypeResolver = $typeResolver instanceof \PoP\ComponentModel\TypeResolvers\UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
            $targetTypeResolverClassDataItems = [];
            foreach ($ids as $resultItemID) {
                if ($targetTypeResolverClass = $typeResolver->getTypeResolverClassForResultItem($resultItemID)) {
                    $targetTypeResolverClassDataItems[$targetTypeResolverClass][] = $resultItemID;
                } else {
                    $resultItemIDTargetTypeResolvers[(string) $resultItemID] = null;
                }
            }
            foreach ($targetTypeResolverClassDataItems as $targetTypeResolverClass => $resultItemIDs) {
                $targetTypeResolver = $instanceManager->getInstance($targetTypeResolverClass);
                $targetResultItemIDTargetTypeResolvers = $this->recursiveGetResultItemIDTargetTypeResolvers($targetTypeResolver, $resultItemIDs);
                foreach ($targetResultItemIDTargetTypeResolvers as $targetResultItemID => $targetTypeResolver) {
                    $resultItemIDTargetTypeResolvers[(string) $targetResultItemID] = $targetTypeResolver;
                }
            }
        } else {
            foreach ($ids as $resultItemID) {
                $resultItemIDTargetTypeResolvers[(string) $resultItemID] = $typeResolver;
            }
        }
        return $resultItemIDTargetTypeResolvers;
    }
    // /**
    //  * Add the type to the ID
    //  *
    //  * @param [type] $resultItem
    //  * @return void
    //  */
    // public function addTypeToID($resultItemID): string
    // {
    //     $instanceManager = InstanceManagerFacade::getInstance();
    //     if ($resultItemTypeResolverClass = $this->getTypeResolverClassForResultItem($resultItemID)) {
    //         $resultItemTypeResolver = $instanceManager->getInstance($resultItemTypeResolverClass);
    //         return UnionTypeHelpers::getDBObjectComposedTypeAndID(
    //             $resultItemTypeResolver,
    //             $resultItemID
    //         );
    //     }
    //     return (string)$resultItemID;
    // }
    /**
     * In order to enable elements from different types (such as posts and users) to have same ID,
     * add the type to the ID
     *
     * @param [type] $resultItem
     * @return void
     * @param object $resultItem
     */
    public function getID($resultItem)
    {
        $targetTypeResolver = $this->getTargetTypeResolver($resultItem);
        if (\is_null($targetTypeResolver)) {
            return null;
        }
        // Add the type to the ID, so that elements of different types can live side by side
        // The type will be removed again in `getIDsToQuery`
        return \PoP\ComponentModel\TypeResolvers\UnionTypeHelpers::getDBObjectComposedTypeAndID($targetTypeResolver, $targetTypeResolver->getID($resultItem));
    }
    public function getTargetTypeResolverClasses() : array
    {
        $typeResolverPickers = $this->getTypeResolverPickers();
        return $this->getTypeResolverClassesFromPickers($typeResolverPickers);
    }
    protected function getTypeResolverClassesFromPickers(array $typeResolverPickers) : array
    {
        return \array_map(function ($typeResolverPicker) {
            return $typeResolverPicker->getTypeResolverClass();
        }, $typeResolverPickers);
    }
    public function getTypeResolverPickers() : array
    {
        if (\is_null($this->typeResolverPickers)) {
            $this->typeResolverPickers = $this->calculateTypeResolverPickers();
        }
        return $this->typeResolverPickers;
    }
    protected function calculateTypeResolverPickers()
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $attachableExtensionManager = \PoP\ComponentModel\Facades\AttachableExtensions\AttachableExtensionManagerFacade::getInstance();
        // Iterate classes from the current class towards the parent classes until finding typeResolver that satisfies processing this field
        $class = \get_called_class();
        $typeResolverPickers = [];
        do {
            // All the pickers and their priorities for this class level
            // Important: do array_reverse to enable more specific hooks, which are initialized later on in the project, to be the chosen ones (if their priority is the same)
            $extensionPickerClassPriorities = \array_reverse($attachableExtensionManager->getExtensionClasses($class, \PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::TYPERESOLVERPICKERS));
            $classPickerPriorities = \array_values($extensionPickerClassPriorities);
            $classPickerClasses = \array_keys($extensionPickerClassPriorities);
            $classPickers = \array_map(function ($extensionClass) use($instanceManager) {
                return $instanceManager->getInstance($extensionClass);
            }, $classPickerClasses);
            // Sort the found pickers by their priority, and then add to the stack of all pickers, for all classes
            // Higher priority means they execute first!
            \array_multisort($classPickerPriorities, \SORT_DESC, \SORT_NUMERIC, $classPickers);
            $typeResolverPickers = \array_merge($typeResolverPickers, $classPickers);
            // Continue iterating for the class parents
        } while ($class = \get_parent_class($class));
        // Validate that all typeResolvers implement the required interface
        if ($typeInterfaceClass = $this->getSchemaTypeInterfaceClass()) {
            $typeResolverClasses = $this->getTypeResolverClassesFromPickers($typeResolverPickers);
            $notImplementingInterfaceTypeResolverClasses = \array_filter($typeResolverClasses, function ($typeResolverClass) use($typeInterfaceClass, $instanceManager) {
                /**
                 * @var TypeResolverInterface
                 */
                $typeResolver = $instanceManager->getInstance($typeResolverClass);
                return !\in_array($typeInterfaceClass, $typeResolver->getAllImplementedInterfaceClasses());
            });
            if ($notImplementingInterfaceTypeResolverClasses) {
                $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
                $typeInterfaceResolver = $instanceManager->getInstance($typeInterfaceClass);
                throw new \Exception(\sprintf($translationAPI->__('UnionTypeResolver \'%s\' (\'%s\') must return results implementing interface \'%s\' (\'%s\'), however its following member TypeResolvers do not: \'%s\'', 'component-model'), $this->getMaybeNamespacedTypeName(), \get_called_class(), $typeInterfaceResolver->getMaybeNamespacedInterfaceName(), $typeInterfaceClass, \implode($translationAPI->__('\', \''), \array_map(function ($typeResolverClass) use($instanceManager, $translationAPI) {
                    /**
                     * @var TypeResolverInterface
                     */
                    $typeResolver = $instanceManager->getInstance($typeResolverClass);
                    return \sprintf($translationAPI->__('%s (%s)'), $typeResolver->getMaybeNamespacedTypeName(), $typeResolverClass);
                }, $notImplementingInterfaceTypeResolverClasses))));
            }
        }
        // Return all the pickers
        return $typeResolverPickers;
    }
    public function getTypeResolverClassForResultItem($resultItemID)
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserTypeResolver)
        foreach ($this->getTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isIDOfType($resultItemID)) {
                // Found it!
                $typeResolverPicker = $maybePicker;
                return $typeResolverPicker->getTypeResolverClass();
            }
        }
        return null;
    }
    /**
     * @param object $resultItem
     */
    public function getTargetTypeResolverPicker($resultItem) : ?\PoP\ComponentModel\TypeResolverPickers\TypeResolverPickerInterface
    {
        // Among all registered fieldresolvers, check if any is able to process the object, through function `process`
        // Important: iterate from back to front, because more general components (eg: Users) are defined first,
        // and dependent components (eg: Communities, Organizations) are defined later
        // Then, more specific implementations (eg: Organizations) must be queried before more general ones (eg: Communities)
        // This is not a problem by making the corresponding field processors inherit from each other, so that the more specific object also handles
        // the fields for the more general ones (eg: TypeResolver_OrganizationUsers extends TypeResolver_CommunityUsers, and TypeResolver_CommunityUsers extends UserTypeResolver)
        foreach ($this->getTypeResolverPickers() as $maybePicker) {
            if ($maybePicker->isInstanceOfType($resultItem)) {
                // Found it!
                return $maybePicker;
            }
        }
        return null;
    }
    /**
     * @param object $resultItem
     */
    public function getTargetTypeResolver($resultItem) : ?\PoP\ComponentModel\TypeResolvers\TypeResolverInterface
    {
        if ($typeResolverPicker = $this->getTargetTypeResolverPicker($resultItem)) {
            $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
            $typeResolverClass = $typeResolverPicker->getTypeResolverClass();
            /**
             * @var TypeResolverInterface
             */
            $typeResolver = $instanceManager->getInstance($typeResolverClass);
            return $typeResolver;
        }
        return null;
    }
    protected function getUnresolvedResultItemIDError($resultItemID)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return new \PoP\ComponentModel\ErrorHandling\Error('unresolved-resultitem-id', \sprintf($translationAPI->__('Either the DataLoader can\'t load data, or no TypeResolver resolves, object with ID \'%s\'', 'pop-component-model'), $resultItemID));
    }
    /**
     * @param object $resultItem
     */
    protected function getUnresolvedResultItemError($resultItem)
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return new \PoP\ComponentModel\ErrorHandling\Error('unresolved-resultitem', \sprintf($translationAPI->__('No TypeResolver resolves object \'%s\'', 'pop-component-model'), \json_encode($resultItem)));
    }
    /**
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue($resultItem, string $field, ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        // Check that a typeResolver from this Union can process this resultItem, or return an arror
        $targetTypeResolver = $this->getTargetTypeResolver($resultItem);
        if (\is_null($targetTypeResolver)) {
            return self::getUnresolvedResultItemError($resultItem);
        }
        // Delegate to that typeResolver to obtain the value
        // Because the schema validation cannot be performed through the UnionTypeResolver, since it depends on each dbObject, indicate that it must be done in resolveValue
        $options[self::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM] = \true;
        return $targetTypeResolver->resolveValue($resultItem, $field, $variables, $expressions, $options);
    }
    protected function addSchemaDefinition(array $stackMessages, array &$generalMessages, array $options = [])
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        // Properties
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME] = $this->getMaybeNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAMESPACED_NAME] = $this->getNamespacedTypeName();
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ELEMENT_NAME] = $this->getTypeName();
        if ($description = $this->getSchemaTypeDescription()) {
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
        }
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_IS_UNION] = \true;
        // If it returns an interface as type, add it to the schemaDefinition
        if ($typeInterfaceClass = $this->getSchemaTypeInterfaceClass()) {
            $typeInterfaceResolver = $instanceManager->getInstance($typeInterfaceClass);
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_RESULTS_IMPLEMENT_INTERFACE] = $typeInterfaceResolver->getMaybeNamespacedInterfaceName();
        }
        // Iterate through the typeResolvers from all the pickers and get their schema definitions
        foreach ($this->getTypeResolverPickers() as $picker) {
            $pickerTypeResolver = $instanceManager->getInstance($picker->getTypeResolverClass());
            $pickerTypeSchemaDefinition = $pickerTypeResolver->getSchemaDefinition($stackMessages, $generalMessages, $options);
            $pickerTypeName = $pickerTypeResolver->getMaybeNamespacedTypeName();
            $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_POSSIBLE_TYPES][$pickerTypeName] = $pickerTypeSchemaDefinition[$pickerTypeName];
        }
    }
    protected function processFlatShapeSchemaDefinition(array $options = [])
    {
        parent::processFlatShapeSchemaDefinition($options);
        $schemaDefinitionService = \PoP\ComponentModel\Facades\Schema\SchemaDefinitionServiceFacade::getInstance();
        $typeSchemaKey = $schemaDefinitionService->getTypeSchemaKey($this);
        // Replace the UnionTypeResolver's types with their typeNames
        $this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_POSSIBLE_TYPES] = \array_keys($this->schemaDefinition[$typeSchemaKey][\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_POSSIBLE_TYPES]);
    }
    /**
     * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used (that depends on each resultItem), it can't resolve error validation
     *
     * @param string $field
     * @param array $variables
     * @return array
     */
    public function resolveSchemaValidationErrorDescriptions(string $field, array &$variables = null) : array
    {
        return [];
    }
}
