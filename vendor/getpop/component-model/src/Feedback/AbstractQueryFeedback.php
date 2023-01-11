<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
/**
 * Error that concern the GraphQL query. the `$astNode` is the
 * node in the AST where the error happens.
 */
abstract class AbstractQueryFeedback extends \PoP\ComponentModel\Feedback\AbstractDocumentFeedback implements \PoP\ComponentModel\Feedback\QueryFeedbackInterface
{
    /**
     * @var AstInterface
     */
    protected $astNode;
    /**
     * @param AstInterface $astNode AST node where the error happens (eg: a Field, a Directive, an Argument, etc)
     * @param array<string,mixed> $extensions
     */
    public function __construct(
        FeedbackItemResolution $feedbackItemResolution,
        AstInterface $astNode,
        /** @var array<string,mixed> */
        array $extensions = []
    )
    {
        $this->astNode = $astNode;
        parent::__construct($feedbackItemResolution, $astNode->getLocation(), $extensions);
    }
    public function getAstNode() : AstInterface
    {
        return $this->astNode;
    }
}
