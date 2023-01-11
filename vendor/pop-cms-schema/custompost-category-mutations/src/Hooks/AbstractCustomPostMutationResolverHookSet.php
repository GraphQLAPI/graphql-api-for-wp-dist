<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\CategoryObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostFilterInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostFilterInputObjectTypeResolverInterface;
abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'maybeAddInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'maybeAddInputFieldDescription']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_TYPE_MODIFIERS, \Closure::fromCallable([$this, 'maybeAddInputFieldTypeModifiers']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function maybeAddInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        // Only for the specific combinations of Type and fieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $inputFieldNameTypeResolvers[MutationInputProperties::CATEGORY_IDS] = $this->getIDScalarTypeResolver();
        return $inputFieldNameTypeResolvers;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver) : bool
    {
        return $inputObjectTypeResolver instanceof CreateCustomPostFilterInputObjectTypeResolverInterface || $inputObjectTypeResolver instanceof UpdateCustomPostFilterInputObjectTypeResolverInterface;
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function maybeAddInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::CATEGORY_IDS || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return \sprintf($this->__('The IDs of the categories to set, of type \'%s\'', 'custompost-category-mutations'), $this->getCategoryTypeResolver()->getMaybeNamespacedTypeName());
    }
    /**
     * @param int $inputFieldTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function maybeAddInputFieldTypeModifiers($inputFieldTypeModifiers, $inputObjectTypeResolver, $inputFieldName) : int
    {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::CATEGORY_IDS || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
    }
    protected abstract function getCategoryTypeResolver() : CategoryObjectTypeResolverInterface;
}
