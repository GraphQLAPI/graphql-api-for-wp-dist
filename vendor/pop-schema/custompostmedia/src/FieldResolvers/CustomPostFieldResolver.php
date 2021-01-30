<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMedia\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoPSchema\CustomPostMedia\FieldInterfaceResolvers\SupportingFeaturedImageFieldInterfaceResolver;
class CustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class];
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [\PoPSchema\CustomPostMedia\FieldInterfaceResolvers\SupportingFeaturedImageFieldInterfaceResolver::class];
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['hasFeaturedImage', 'featuredImage'];
    }
    /**
     * By returning `null`, the schema definition comes from the interface
     *
     * @return void
     */
    public function getSchemaDefinitionResolver(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : ?\PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface
    {
        return null;
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
        $cmsmediapostsapi = \PoPSchema\Media\PostsFunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'hasFeaturedImage':
                return $cmsmediapostsapi->hasCustomPostThumbnail($typeResolver->getID($post));
            case 'featuredImage':
                return $cmsmediapostsapi->getCustomPostThumbnailID($typeResolver->getID($post));
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'featuredImage':
                $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
                /**
                 * @var SupportingFeaturedImageFieldInterfaceResolver
                 */
                $fieldInterfaceResolver = $instanceManager->getInstance(\PoPSchema\CustomPostMedia\FieldInterfaceResolvers\SupportingFeaturedImageFieldInterfaceResolver::class);
                return $fieldInterfaceResolver->getFieldTypeResolverClass($fieldName);
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
