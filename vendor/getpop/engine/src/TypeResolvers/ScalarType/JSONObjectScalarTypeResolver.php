<?php

declare (strict_types=1);
namespace PoP\Engine\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ScalarType\AbstractScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;
/**
 * GraphQL Custom Scalar representing a JSON Object on the client-side,
 * handled via an stdClass object on the server-side
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class JSONObjectScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName() : string
    {
        return 'JSONObject';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Custom scalar representing a JSON Object of unrestricted shape', 'component-model');
    }
    public function getSpecifiedByURL() : ?string
    {
        return 'https://datatracker.ietf.org/doc/html/rfc7159';
    }
    /**
     * @param string|int|float|bool|\stdClass $inputValue
     * @return string|int|float|bool|object|null
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function coerceValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore)
    {
        if (!$inputValue instanceof stdClass) {
            $this->addDefaultError($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
            return null;
        }
        return $inputValue;
    }
}
