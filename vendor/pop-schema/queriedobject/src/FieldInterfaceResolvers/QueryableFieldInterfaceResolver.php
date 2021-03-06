<?php

declare (strict_types=1);
namespace PoPSchema\QueriedObject\FieldInterfaceResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver;
class QueryableFieldInterfaceResolver extends \PoP\ComponentModel\FieldInterfaceResolvers\AbstractSchemaFieldInterfaceResolver
{
    public const NAME = 'Queryable';
    public function getInterfaceName() : string
    {
        return self::NAME;
    }
    public function getSchemaInterfaceDescription() : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        return $translationAPI->__('Entities that can be queried through an URL', 'queriedobject');
    }
    public static function getFieldNamesToImplement() : array
    {
        return ['url', 'slug'];
    }
    public function getSchemaFieldType(string $fieldName) : ?string
    {
        $types = ['url' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_URL, 'slug' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING];
        return $types[$fieldName] ?? parent::getSchemaFieldType($fieldName);
    }
    public function getSchemaFieldDescription(string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['url' => $translationAPI->__('URL to query the object', 'queriedobject'), 'slug' => $translationAPI->__('URL\'s slug', 'queriedobject')];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}
