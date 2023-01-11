<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
/**
 * The SchemaFeedback is used to store errors that relate to the
 * schema, and for which some Field value must be set to `null`.
 * The `$astNode` is where the error happens (eg: it could be a
 * Field or Directive or Argument), and the `$fields` are all the
 * Fields that must be set to `null` (we'd normally the field to be only 1,
 * but if the $astNode is a Directive, then it can affect multiple Fields).
 */
class SchemaFeedback extends \PoP\ComponentModel\Feedback\AbstractQueryFeedback implements \PoP\ComponentModel\Feedback\SchemaFeedbackInterface
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface
     */
    protected $relationalTypeResolver;
    /**
     * @var FieldInterface[]
     */
    protected $fields;
    /**
     * @param FieldInterface[] $fields All the affected fields (eg: those to be resolved as `null` in case of error)
     * @param array<string,mixed> $extensions
     */
    public function __construct(FeedbackItemResolution $feedbackItemResolution, AstInterface $astNode, RelationalTypeResolverInterface $relationalTypeResolver, array $fields, array $extensions = [])
    {
        $this->relationalTypeResolver = $relationalTypeResolver;
        $this->fields = $fields;
        parent::__construct($feedbackItemResolution, $astNode, $extensions);
    }
    /**
     * @param FieldInterface[] $fields
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackInterface $objectTypeFieldResolutionFeedback
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     */
    public static function fromObjectTypeFieldResolutionFeedback($objectTypeFieldResolutionFeedback, $relationalTypeResolver, $fields) : self
    {
        return new self($objectTypeFieldResolutionFeedback->getFeedbackItemResolution(), $objectTypeFieldResolutionFeedback->getAstNode(), $relationalTypeResolver, $fields, $objectTypeFieldResolutionFeedback->getExtensions());
    }
    public function getRelationalTypeResolver() : RelationalTypeResolverInterface
    {
        return $this->relationalTypeResolver;
    }
    /**
     * @return FieldInterface[]
     */
    public function getFields() : array
    {
        return $this->fields;
    }
}
