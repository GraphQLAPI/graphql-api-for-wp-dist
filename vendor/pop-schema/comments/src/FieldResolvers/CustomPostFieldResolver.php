<?php

declare (strict_types=1);
namespace PoPSchema\Comments\FieldResolvers;

use PoPSchema\Comments\Constants\Status;
use PoP\LooseContracts\Facades\NameResolverFacade;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\Comments\FieldInterfaceResolvers\CommentableFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\FieldSchemaDefinitionResolverInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
class CustomPostFieldResolver extends \PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver
{
    public static function getClassesToAttachTo() : array
    {
        return [\PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver::class];
    }
    public static function getImplementedInterfaceClasses() : array
    {
        return [\PoPSchema\Comments\FieldInterfaceResolvers\CommentableFieldInterfaceResolver::class];
    }
    public static function getFieldNamesToResolve() : array
    {
        return ['areCommentsOpen', 'commentCount', 'hasComments', 'comments'];
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
        $cmscommentsapi = \PoPSchema\Comments\FunctionAPIFactory::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            case 'areCommentsOpen':
                return $cmscommentsapi->areCommentsOpen($typeResolver->getID($post));
            case 'commentCount':
                return $cmscommentsapi->getCommentNumber($typeResolver->getID($post));
            case 'hasComments':
                return $typeResolver->resolveValue($post, 'commentCount', $variables, $expressions, $options) > 0;
            case 'comments':
                $query = array(
                    'status' => \PoPSchema\Comments\Constants\Status::APPROVED,
                    // 'type' => 'comment', // Only comments, no trackbacks or pingbacks
                    'customPostID' => $typeResolver->getID($post),
                    // The Order must always be date > ASC so the jQuery works in inserting sub-comments in already-created parent comments
                    'order' => 'ASC',
                    'orderby' => \PoP\LooseContracts\Facades\NameResolverFacade::getInstance()->getName('popcms:dbcolumn:orderby:comments:date'),
                );
                $options = ['return-type' => \PoPSchema\SchemaCommons\DataLoading\ReturnTypes::IDS];
                $this->addFilterDataloadQueryArgs($options, $typeResolver, $fieldName, $fieldArgs);
                return $cmscommentsapi->getComments($query, $options);
        }
        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
    public function resolveFieldTypeResolverClass(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver, string $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'comments':
                return \PoPSchema\Comments\TypeResolvers\CommentTypeResolver::class;
        }
        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
