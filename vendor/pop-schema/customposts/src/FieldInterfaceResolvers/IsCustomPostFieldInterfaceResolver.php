<?php

declare (strict_types=1);
namespace PoPSchema\CustomPosts\FieldInterfaceResolvers;

use PoPSchema\CustomPosts\Types\Status;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\CustomPosts\Enums\CustomPostStatusEnum;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\EnumTypeFieldInterfaceSchemaDefinitionResolverTrait;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
class IsCustomPostFieldInterfaceResolver extends \PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver
{
    use EnumTypeFieldInterfaceSchemaDefinitionResolverTrait;
    public const NAME = 'IsCustomPost';
    public function getInterfaceName() : string
    {
        return self::NAME;
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [\PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver::class];
    }
    public function getSchemaInterfaceDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Entities representing a custom post', 'customposts');
    }
    public static function getFieldNamesToImplement() : array
    {
        return \array_merge(parent::getFieldNamesToImplement(), ['content', 'status', 'isStatus', 'date', 'datetime', 'title', 'excerpt', 'customPostType']);
    }
    public function getSchemaFieldType(string $fieldName) : ?string
    {
        $types = ['content' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'status' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, 'isStatus' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'date' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_DATE, 'datetime' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_DATE, 'title' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'excerpt' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'customPostType' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }
    public function isSchemaFieldResponseNonNullable(string $fieldName) : bool
    {
        /**
         * Please notice that the URL, slug, title and excerpt are nullable,
         * and content is not!
         */
        switch ($fieldName) {
            case 'content':
            case 'status':
            case 'isStatus':
            case 'date':
            case 'datetime':
            case 'customPostType':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($fieldName);
    }
    public function getSchemaFieldDescription(string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['content' => $translationAPI->__('Custom post content', 'customposts'), 'status' => $translationAPI->__('Custom post status', 'customposts'), 'isStatus' => $translationAPI->__('Is the custom post in the given status?', 'customposts'), 'date' => $translationAPI->__('Custom post published date', 'customposts'), 'datetime' => $translationAPI->__('Custom post published date and time', 'customposts'), 'title' => $translationAPI->__('Custom post title', 'customposts'), 'excerpt' => $translationAPI->__('Custom post excerpt', 'customposts'), 'customPostType' => $translationAPI->__('Custom post type', 'customposts')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
    public function getSchemaFieldArgs(string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'date':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'format', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Date format, as defined in %s', 'customposts'), 'https://www.php.net/manual/en/function.date.php'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => $cmsengineapi->getOption(\PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))]]);
            case 'datetime':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'format', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Date and time format, as defined in %s. Default value: \'%s\' (for current year date) or \'%s\' (otherwise)', 'customposts'), 'https://www.php.net/manual/en/function.date.php', 'j M, H:i', 'j M Y, H:i')]]);
            case 'isStatus':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostStatusEnum::class);
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'status', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The status to check if the post has', 'customposts'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $customPostStatusEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => [\PoPSchema\CustomPosts\Types\Status::PUBLISHED => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPosts\Types\Status::PUBLISHED], \PoPSchema\CustomPosts\Types\Status::PENDING => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPosts\Types\Status::PENDING], \PoPSchema\CustomPosts\Types\Status::DRAFT => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPosts\Types\Status::DRAFT], \PoPSchema\CustomPosts\Types\Status::TRASH => [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CustomPosts\Types\Status::TRASH]], \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]]);
            case 'content':
                /**
                 * @var CustomPostContentFormatEnum
                 */
                $customPostContentFormatEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum::class);
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'format', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ENUM, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The format of the content', 'customposts'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_NAME => $customPostContentFormatEnum->getName(), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_ENUM_VALUES => \PoP\ComponentModel\Schema\SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions($customPostContentFormatEnum->getValues()), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => self::getDefaultContentFormatValue()]]);
        }
        return $schemaFieldArgs;
    }
    public static function getDefaultContentFormatValue() : string
    {
        return \PoPSchema\CustomPosts\Enums\CustomPostContentFormatEnum::HTML;
    }
    protected function getSchemaDefinitionEnumName(string $fieldName) : ?string
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostStatusEnum::class);
                return $customPostStatusEnum->getName();
        }
        return null;
    }
    protected function getSchemaDefinitionEnumValues(string $fieldName) : ?array
    {
        $instanceManager = \PoP\ComponentModel\Facades\Instances\InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'status':
                /**
                 * @var CustomPostStatusEnum
                 */
                $customPostStatusEnum = $instanceManager->getInstance(\PoPSchema\CustomPosts\Enums\CustomPostStatusEnum::class);
                return \array_merge($customPostStatusEnum->getValues(), []);
        }
        return null;
    }
    /**
     * @todo Extract to documentation before deleting this code
     */
    // protected function getSchemaDefinitionEnumValueDeprecationDescriptions(string $fieldName): ?array
    // {
    //     $translationAPI = TranslationAPIFacade::getInstance();
    //     switch ($fieldName) {
    //         case 'status':
    //             return [
    //                 'trashed' => sprintf(
    //                     $translationAPI->__('Using \'%s\' instead', 'customposts'),
    //                     Status::TRASH
    //                 ),
    //             ];
    //     }
    //     return null;
    // }
    protected function getSchemaDefinitionEnumValueDescriptions(string $fieldName) : ?array
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'status':
                return [\PoPSchema\CustomPosts\Types\Status::PUBLISHED => $translationAPI->__('Published content', 'customposts'), \PoPSchema\CustomPosts\Types\Status::PENDING => $translationAPI->__('Pending content', 'customposts'), \PoPSchema\CustomPosts\Types\Status::DRAFT => $translationAPI->__('Draft content', 'customposts'), \PoPSchema\CustomPosts\Types\Status::TRASH => $translationAPI->__('Trashed content', 'customposts')];
        }
        return null;
    }
}
