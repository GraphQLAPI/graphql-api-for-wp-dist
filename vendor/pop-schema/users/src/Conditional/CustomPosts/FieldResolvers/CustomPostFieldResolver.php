<?php

declare (strict_types=1);
namespace PoPSchema\Users\Conditional\CustomPosts\FieldResolvers;

use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoPSchema\Users\FieldInterfaceResolvers\WithAuthorFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Users\Conditional\CustomPosts\Facades\CustomPostUserTypeAPIFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface;
class CustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class];
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [\PoPSchema\Users\FieldInterfaceResolvers\WithAuthorFieldInterfaceResolver::class];
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['author'];
    }
    protected function getWithAuthorFieldInterfaceResolverInstance() : \PoP\ComponentModel\FieldInterfaceResolvers\FieldInterfaceResolverInterface
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        /**
         * @var WithAuthorFieldInterfaceResolver
         */
        $resolver = $instanceManager->getInstance(\PoPSchema\Users\FieldInterfaceResolvers\WithAuthorFieldInterfaceResolver::class);
        return $resolver;
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getSchemaFieldType($fieldName);
        }
        return parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['author' => $translationAPI->__('The post\'s author', '')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->isSchemaFieldResponseNonNullable($fieldName);
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $customPostUserTypeAPI = \PoPSchema\Users\Conditional\CustomPosts\Facades\CustomPostUserTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'author':
                return $customPostUserTypeAPI->getAuthorID($resultItem);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                $fieldInterfaceResolver = $this->getWithAuthorFieldInterfaceResolverInstance();
                return $fieldInterfaceResolver->getFieldTypeResolverClass($fieldName);
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
