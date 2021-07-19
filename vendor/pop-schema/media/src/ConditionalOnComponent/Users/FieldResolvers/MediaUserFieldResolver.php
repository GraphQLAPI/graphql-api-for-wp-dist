<?php

declare (strict_types=1);
namespace PoPSchema\Media\ConditionalOnComponent\Users\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
class MediaUserFieldResolver extends AbstractDBDataFieldResolver
{
    /**
     * @var \PoPSchema\Media\ConditionalOnComponent\Users\TypeAPIs\MediaTypeAPIInterface
     */
    protected $mediaTypeAPI;
    public function __construct(TranslationAPIInterface $translationAPI, HooksAPIInterface $hooksAPI, InstanceManagerInterface $instanceManager, FieldQueryInterpreterInterface $fieldQueryInterpreter, NameResolverInterface $nameResolver, CMSServiceInterface $cmsService, SemverHelperServiceInterface $semverHelperService, MediaTypeAPIInterface $mediaTypeAPI)
    {
        $this->mediaTypeAPI = $mediaTypeAPI;
        parent::__construct($translationAPI, $hooksAPI, $instanceManager, $fieldQueryInterpreter, $nameResolver, $cmsService, $semverHelperService);
    }
    public function getClassesToAttachTo() : array
    {
        return array(MediaTypeResolver::class);
    }
    public function getFieldNamesToResolve() : array
    {
        return ['author'];
    }
    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName) : string
    {
        $types = ['author' => SchemaDefinition::TYPE_ID];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $descriptions = ['author' => $this->translationAPI->__('Media element\'s author', 'pop-media')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }
    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     * @param object $resultItem
     */
    public function resolveValue(TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $media = $resultItem;
        switch ($fieldName) {
            case 'author':
                return $this->mediaTypeAPI->getMediaAuthorId($media);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'author':
                return UserTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
