<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Media\TypeResolvers\InputObjectType\MediaItemByInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput;

class InputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput|null
     */
    private $slugFilterInput;

    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    final public function setStringScalarTypeResolver($stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\SchemaCommons\FilterInputs\SlugFilterInput $slugFilterInput
     */
    final public function setSlugFilterInput($slugFilterInput): void
    {
        $this->slugFilterInput = $slugFilterInput;
    }
    final protected function getSlugFilterInput(): SlugFilterInput
    {
        /** @var SlugFilterInput */
        return $this->slugFilterInput = $this->slugFilterInput ?? $this->instanceManager->getInstance(SlugFilterInput::class);
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
        if (!($inputObjectTypeResolver instanceof MediaItemByInputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'slug' => $this->getStringScalarTypeResolver(),
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
        if (!($inputObjectTypeResolver instanceof MediaItemByInputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'slug':
                return $this->__('Query media item by slug', 'media');
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
        if (!($inputObjectTypeResolver instanceof MediaItemByInputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'slug':
                return $this->getSlugFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
