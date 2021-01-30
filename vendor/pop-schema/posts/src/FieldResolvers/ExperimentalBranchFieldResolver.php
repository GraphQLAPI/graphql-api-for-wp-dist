<?php

declare (strict_types=1);
namespace PoPSchema\Posts\FieldResolvers;

use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\CustomPostFieldResolver;
class ExperimentalBranchFieldResolver extends \PoPSchema\CustomPosts\FieldResolvers\CustomPostFieldResolver
{
    /**
     * Attach to Posts only
     *
     * @return array
     */
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\Posts\TypeResolvers\PostTypeResolver::class];
    }
    /**
     * The priority with which to attach to the class. The higher the priority, the sooner it will be processed
     * Have a higher priority than the class it extends, as to override it
     *
     * @return integer|null
     */
    public static function getPriorityToAttachClasses() : ?int
    {
        return 20;
    }
    public function resolveCanProcess(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []) : bool
    {
        // Must specify fieldArg 'branch' => 'experimental'
        return isset($fieldArgs['branch']) && $fieldArgs['branch'] == 'experimental';
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['excerpt'];
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [];
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'excerpt':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'branch', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The branch name, set to value \'experimental\', enabling to use this fieldResolver', 'pop-posts')], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'length', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_INT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Maximum length for the except, in number of characters', 'pop-posts')], [\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'more', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('String to append at the end of the excerpt (if it is shortened by the \'length\' parameter)', 'pop-posts')]]);
        }
        return $schemaFieldArgs;
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
        switch ($fieldName) {
            case 'excerpt':
                // Obtain the required parameter values (or default to some basic values)
                $length = $fieldArgs['length'] ?? 100;
                $more = $fieldArgs['more'] ?? '';
                $excerpt = parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                return \strlen($excerpt) > $length ? \mb_substr($excerpt, 0, $length) . $more : $excerpt;
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
