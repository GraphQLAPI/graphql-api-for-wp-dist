<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Posts\TypeResolvers\InputObjectType\AbstractPostsFilterInputObjectTypeResolver;
use PoPWPSchema\Posts\FilterInputs\IsStickyFilterInput;

class InputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoPWPSchema\Posts\FilterInputs\IsStickyFilterInput|null
     */
    private $isStickyFilterInput;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver $booleanScalarTypeResolver
     */
    final public function setBooleanScalarTypeResolver($booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        /** @var BooleanScalarTypeResolver */
        return $this->booleanScalarTypeResolver = $this->booleanScalarTypeResolver ?? $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    /**
     * @param \PoPWPSchema\Posts\FilterInputs\IsStickyFilterInput $isStickyFilterInput
     */
    final public function setIsStickyFilterInput($isStickyFilterInput): void
    {
        $this->isStickyFilterInput = $isStickyFilterInput;
    }
    final protected function getIsStickyFilterInput(): IsStickyFilterInput
    {
        /** @var IsStickyFilterInput */
        return $this->isStickyFilterInput = $this->isStickyFilterInput ?? $this->instanceManager->getInstance(IsStickyFilterInput::class);
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']),
            10,
            2
        );
        App::addFilter(
            HookNames::INPUT_FIELD_DESCRIPTION,
            \Closure::fromCallable([$this, 'getInputFieldDescription']),
            10,
            3
        );
        App::addFilter(
            HookNames::INPUT_FIELD_FILTER_INPUT,
            \Closure::fromCallable([$this, 'getInputFieldFilterInput']),
            10,
            3
        );
    }

    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver): array
    {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'isSticky' => $this->getBooleanScalarTypeResolver(),
            ]
        );
    }

    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDescription(
        $inputFieldDescription,
        $inputObjectTypeResolver,
        $inputFieldName
    ): ?string {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'isSticky':
                return $this->__('Filter by sticky posts', 'posts');
            default:
                return $inputFieldDescription;
        }
    }

    /**
     * @param \PoP\ComponentModel\FilterInputs\FilterInputInterface|null $inputFieldFilterInput
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldFilterInput, $inputObjectTypeResolver, $inputFieldName): ?FilterInputInterface
    {
        if (!($inputObjectTypeResolver instanceof AbstractPostsFilterInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'isSticky':
                return $this->getIsStickyFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
