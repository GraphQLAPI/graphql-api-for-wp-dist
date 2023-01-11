<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostCategoryMutations\Module;
use PoPCMSSchema\CustomPostCategoryMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostCategoryMutations\Constants\MutationInputProperties;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
abstract class AbstractCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver implements \PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\SetCategoriesOnCustomPostObjectTypeFieldResolverInterface
{
    use \PoPCMSSchema\CustomPostCategoryMutations\FieldResolvers\ObjectType\SetCategoriesOnCustomPostObjectTypeFieldResolverTrait;
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [\get_class($this->getCustomPostObjectTypeResolver())];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['setCategories'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setCategories':
                return \sprintf($this->__('Set categories on the %s', 'custompost-category-mutations'), $this->getEntityName());
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        switch ($fieldName) {
            case 'setCategories':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'setCategories':
                return [MutationInputProperties::INPUT => $this->getCustomPostSetCategoriesFilterInputObjectTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ([$fieldName => $fieldArgName]) {
            case ['setCategories' => MutationInputProperties::INPUT]:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function validateMutationOnObject($objectTypeResolver, $fieldName) : bool
    {
        switch ($fieldName) {
            case 'setCategories':
                return \true;
            default:
                return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object) : array
    {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object);
        $customPost = $object;
        switch ($field->getName()) {
            case 'setCategories':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldMutationResolver($objectTypeResolver, $fieldName) : ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMutations();
        switch ($fieldName) {
            case 'setCategories':
                return $usePayloadableCustomPostCategoryMutations ? $this->getPayloadableSetCategoriesMutationResolver() : $this->getSetCategoriesMutationResolver();
            default:
                return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostCategoryMutations = $moduleConfiguration->usePayloadableCustomPostCategoryMutations();
        if ($usePayloadableCustomPostCategoryMutations) {
            switch ($fieldName) {
                case 'setCategories':
                    return $this->getCustomPostSetCategoriesMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'setCategories':
                return $this->getCustomPostObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    protected abstract function getCustomPostSetCategoriesMutationPayloadObjectTypeResolver() : ConcreteTypeResolverInterface;
}
