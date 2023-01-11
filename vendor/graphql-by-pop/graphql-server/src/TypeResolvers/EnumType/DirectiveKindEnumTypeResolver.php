<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use PoP\Root\App;
use stdClass;
class DirectiveKindEnumTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType\AbstractIntrospectionEnumTypeResolver
{
    public function getTypeName() : string
    {
        return 'DirectiveKindEnum';
    }
    /**
     * @return string[]
     */
    public function getEnumValues() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return \array_merge([DirectiveKinds::QUERY, DirectiveKinds::SCHEMA], $moduleConfiguration->enableComposableDirectives() ? [DirectiveKinds::INDEXING] : []);
    }
    /**
     * Convert the DirectiveKind enum from UPPERCASE as input, to lowercase
     * as defined in DirectiveKinds.php
     * @param string|int|float|bool|\stdClass $inputValue
     * @return string|int|float|bool|object|null
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\AstInterface $astNode
     * @param \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore
     */
    public function coerceValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore)
    {
        // Validate type first
        if (!\is_string($inputValue)) {
            return parent::coerceValue($inputValue, $astNode, $objectTypeFieldResolutionFeedbackStore);
        }
        return parent::coerceValue(\strtolower($inputValue), $astNode, $objectTypeFieldResolutionFeedbackStore);
    }
    /**
     * Convert back from lowercase to UPPERCASE
     *
     * @return string|int|float|bool|mixed[]|stdClass
     * @param string|int|float|bool|object $scalarValue
     */
    public function serialize($scalarValue)
    {
        /** @var string $scalarValue */
        return \strtoupper($scalarValue);
    }
}
