<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\InputObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
/**
 * Oneof InputObject Type, as proposed for the GraphQL spec:
 *
 * @see https://github.com/graphql/graphql-spec/pull/825
 */
abstract class AbstractOneofQueryableInputObjectTypeResolver extends \PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractQueryableInputObjectTypeResolver implements \PoP\ComponentModel\TypeResolvers\InputObjectType\OneofInputObjectTypeResolverInterface
{
    use \PoP\ComponentModel\TypeResolvers\InputObjectType\OneofInputObjectTypeResolverTrait;
    /**
     * Validate that there is exactly one input set
     * @param \stdClass $inputValue
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    protected function coerceInputObjectValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore) : ?stdClass
    {
        $this->validateOneofInputObjectValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }
        return parent::coerceInputObjectValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
    }
}
