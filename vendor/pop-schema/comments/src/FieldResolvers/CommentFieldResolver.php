<?php

declare (strict_types=1);
namespace PoPSchema\Comments\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
class CommentFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return array(\PoPSchema\Comments\TypeResolvers\CommentTypeResolver::class);
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['content', 'authorName', 'authorURL', 'authorEmail', 'customPost', 'customPostID', 'approved', 'type', 'parent', 'date'];
    }
    public function getSchemaFieldType(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $types = ['content' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'authorName' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'authorURL' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_URL, 'authorEmail' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_EMAIL, 'customPost' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'customPostID' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'approved' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_BOOL, 'type' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, 'parent' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_ID, 'date' => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_DATE];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }
    public function isSchemaFieldResponseNonNullable(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : bool
    {
        switch ($fieldName) {
            case 'content':
            case 'customPost':
            case 'customPostID':
            case 'approved':
            case 'type':
            case 'date':
                return \true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }
    public function getSchemaFieldDescription(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $descriptions = ['content' => $translationAPI->__('Comment\'s content', 'pop-comments'), 'authorName' => $translationAPI->__('Comment author\'s name', 'pop-comments'), 'authorURL' => $translationAPI->__('Comment author\'s URL', 'pop-comments'), 'authorEmail' => $translationAPI->__('Comment author\'s email', 'pop-comments'), 'customPost' => $translationAPI->__('Custom post to which the comment was added', 'pop-comments'), 'customPostID' => $translationAPI->__('ID of the custom post to which the comment was added', 'pop-comments'), 'approved' => $translationAPI->__('Is the comment approved?', 'pop-comments'), 'type' => $translationAPI->__('Type of comment', 'pop-comments'), 'parent' => $translationAPI->__('Parent comment (if this comment is a response to another one)', 'pop-comments'), 'date' => $translationAPI->__('Date when the comment was added', 'pop-comments')];
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
    public function resolveValue(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, $resultItem, string $fieldName, array $fieldArgs = [], ?array $variables = null, ?array $expressions = null, array $options = [])
    {
        $cmscommentsresolver = \PoPSchema\Comments\ObjectPropertyResolverFactory::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $comment = $resultItem;
        switch ($fieldName) {
            case 'content':
                return $cmscommentsresolver->getCommentContent($comment);
            case 'authorName':
                return $cmsusersapi->getUserDisplayName($cmscommentsresolver->getCommentUserId($comment));
            case 'authorURL':
                return $cmsusersapi->getUserURL($cmscommentsresolver->getCommentUserId($comment));
            case 'authorEmail':
                return $cmsusersapi->getUserEmail($cmscommentsresolver->getCommentUserId($comment));
            case 'customPost':
            case 'customPostID':
                return $cmscommentsresolver->getCommentPostId($comment);
            case 'approved':
                return $cmscommentsresolver->isCommentApproved($comment);
            case 'type':
                return $cmscommentsresolver->getCommentType($comment);
            case 'parent':
                return $cmscommentsresolver->getCommentParent($comment);
            case 'date':
                return $cmsengineapi->getDate($fieldArgs['format'], $cmscommentsresolver->getCommentDateGmt($comment));
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function getSchemaFieldArgs(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = \PoP\Translation\Facades\TranslationAPIFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'date':
                return \array_merge($schemaFieldArgs, [[\PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_NAME => 'format', \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_TYPE => \PoP\ComponentModel\Schema\SchemaDefinition::TYPE_STRING, \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DESCRIPTION => \sprintf($translationAPI->__('Date format, as defined in %s', 'pop-comments'), 'https://www.php.net/manual/en/function.date.php'), \PoP\ComponentModel\Schema\SchemaDefinition::ARGNAME_DEFAULT_VALUE => $cmsengineapi->getOption(\PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:option:dateFormat'))]]);
        }
        return $schemaFieldArgs;
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'customPost':
                return \PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(\PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver::class);
            case 'parent':
                return \PoPSchema\Comments\TypeResolvers\CommentTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
