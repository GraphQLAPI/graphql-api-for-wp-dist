<?php

declare (strict_types=1);
namespace PoPSchema\CommentMutations\Schema;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
class SchemaDefinitionHelpers
{
    /**
     * @var mixed[]
     */
    private static $schemaFieldArgsCache = [];
    public static function getAddCommentToCustomPostSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName, bool $addCustomPostID, bool $addParentCommentID, bool $isParentCommentMandatory = \false) : array
    {
        $key = \get_class($typeResolver) . '-' . $fieldName;
        if (!isset(self::$schemaFieldArgsCache[$key])) {
            $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
            self::$schemaFieldArgsCache[$key] = \array_merge($addCustomPostID ? [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties::CUSTOMPOST_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the custom post to add a comment to', 'comment-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]] : [], [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties::COMMENT, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The comment to add', 'comment-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => \true]], $addParentCommentID ? [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => \PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties::PARENT_COMMENT_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The ID of the parent comment', 'comment-mutations'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_MANDATORY => $isParentCommentMandatory]] : []);
        }
        return self::$schemaFieldArgsCache[$key];
    }
}
