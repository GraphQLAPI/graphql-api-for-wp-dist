<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPWPSchema\CustomPosts\FilterInputs\HasPasswordFilterInput;
use PoPWPSchema\CustomPosts\FilterInputs\PasswordFilterInput;

abstract class AbstractAddCustomPostPasswordInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver|null
     */
    private $booleanScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPWPSchema\CustomPosts\FilterInputs\HasPasswordFilterInput|null
     */
    private $hasPasswordFilterInput;
    /**
     * @var \PoPWPSchema\CustomPosts\FilterInputs\PasswordFilterInput|null
     */
    private $passwordFilterInput;

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
     * @param \PoPWPSchema\CustomPosts\FilterInputs\HasPasswordFilterInput $hasPasswordFilterInput
     */
    final public function setHasPasswordFilterInput($hasPasswordFilterInput): void
    {
        $this->hasPasswordFilterInput = $hasPasswordFilterInput;
    }
    final protected function getHasPasswordFilterInput(): HasPasswordFilterInput
    {
        /** @var HasPasswordFilterInput */
        return $this->hasPasswordFilterInput = $this->hasPasswordFilterInput ?? $this->instanceManager->getInstance(HasPasswordFilterInput::class);
    }
    /**
     * @param \PoPWPSchema\CustomPosts\FilterInputs\PasswordFilterInput $passwordFilterInput
     */
    final public function setPasswordFilterInput($passwordFilterInput): void
    {
        $this->passwordFilterInput = $passwordFilterInput;
    }
    final protected function getPasswordFilterInput(): PasswordFilterInput
    {
        /** @var PasswordFilterInput */
        return $this->passwordFilterInput = $this->passwordFilterInput ?? $this->instanceManager->getInstance(PasswordFilterInput::class);
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
            HookNames::ADMIN_INPUT_FIELD_NAMES,
            \Closure::fromCallable([$this, 'getSensitiveInputFieldNames']),
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
            HookNames::INPUT_FIELD_DEFAULT_VALUE,
            \Closure::fromCallable([$this, 'getInputFieldDefaultValue']),
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
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return array_merge(
            $inputFieldNameTypeResolvers,
            [
                'hasPassword' => $this->getBooleanScalarTypeResolver(),
                'password' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    abstract protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool;

    /**
     * @param string[] $inputFieldNames
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getSensitiveInputFieldNames($inputFieldNames, $inputObjectTypeResolver): array
    {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNames;
        }
        return array_merge(
            $inputFieldNames,
            [
                'hasPassword',
                'password',
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
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'hasPassword':
                return $this->__('Indicate if to include custom posts which are password-protected. Pass `null` to fetch both with/out password', 'customposts');
            case 'password':
                return $this->__('Include custom posts protected by a specific password', 'customposts');
            default:
                return $inputFieldDescription;
        }
    }

    /**
     * @param mixed $inputFieldDefaultValue
     * @return mixed
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDefaultValue(
        $inputFieldDefaultValue,
        $inputObjectTypeResolver,
        $inputFieldName
    ) {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDefaultValue;
        }
        switch ($inputFieldName) {
            case 'hasPassword':
                return false;
            default:
                return $inputFieldDefaultValue;
        }
    }

    /**
     * @param \PoP\ComponentModel\FilterInputs\FilterInputInterface|null $inputFieldFilterInput
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldFilterInput, $inputObjectTypeResolver, $inputFieldName): ?FilterInputInterface
    {
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'hasPassword':
                return $this->getHasPasswordFilterInput();
            case 'password':
                return $this->getPasswordFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
