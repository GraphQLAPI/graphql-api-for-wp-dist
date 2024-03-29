<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Feedback;

use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
interface QueryFeedbackInterface extends \PoP\ComponentModel\Feedback\DocumentFeedbackInterface
{
    public function getAstNode() : AstInterface;
}
