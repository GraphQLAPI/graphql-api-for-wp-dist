<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\RootCommentsFilterInputObjectTypeResolver;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput;
class InputObjectTypeHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput|null
     */
    private $customPostAuthorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput|null
     */
    private $excludeCustomPostAuthorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput|null
     */
    private $authorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput|null
     */
    private $excludeAuthorIDsFilterInput;
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver $idScalarTypeResolver
     */
    public final function setIDScalarTypeResolver($idScalarTypeResolver) : void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    protected final function getIDScalarTypeResolver() : IDScalarTypeResolver
    {
        /** @var IDScalarTypeResolver */
        return $this->idScalarTypeResolver = $this->idScalarTypeResolver ?? $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\CustomPostAuthorIDsFilterInput $customPostAuthorIDsFilterInput
     */
    public final function setCustomPostAuthorIDsFilterInput($customPostAuthorIDsFilterInput) : void
    {
        $this->customPostAuthorIDsFilterInput = $customPostAuthorIDsFilterInput;
    }
    protected final function getCustomPostAuthorIDsFilterInput() : CustomPostAuthorIDsFilterInput
    {
        /** @var CustomPostAuthorIDsFilterInput */
        return $this->customPostAuthorIDsFilterInput = $this->customPostAuthorIDsFilterInput ?? $this->instanceManager->getInstance(CustomPostAuthorIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Comments\ConditionalOnModule\Users\FilterInputs\ExcludeCustomPostAuthorIDsFilterInput $excludeCustomPostAuthorIDsFilterInput
     */
    public final function setExcludeCustomPostAuthorIDsFilterInput($excludeCustomPostAuthorIDsFilterInput) : void
    {
        $this->excludeCustomPostAuthorIDsFilterInput = $excludeCustomPostAuthorIDsFilterInput;
    }
    protected final function getExcludeCustomPostAuthorIDsFilterInput() : ExcludeCustomPostAuthorIDsFilterInput
    {
        /** @var ExcludeCustomPostAuthorIDsFilterInput */
        return $this->excludeCustomPostAuthorIDsFilterInput = $this->excludeCustomPostAuthorIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeCustomPostAuthorIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput $authorIDsFilterInput
     */
    public final function setAuthorIDsFilterInput($authorIDsFilterInput) : void
    {
        $this->authorIDsFilterInput = $authorIDsFilterInput;
    }
    protected final function getAuthorIDsFilterInput() : AuthorIDsFilterInput
    {
        /** @var AuthorIDsFilterInput */
        return $this->authorIDsFilterInput = $this->authorIDsFilterInput ?? $this->instanceManager->getInstance(AuthorIDsFilterInput::class);
    }
    /**
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput $excludeAuthorIDsFilterInput
     */
    public final function setExcludeAuthorIDsFilterInput($excludeAuthorIDsFilterInput) : void
    {
        $this->excludeAuthorIDsFilterInput = $excludeAuthorIDsFilterInput;
    }
    protected final function getExcludeAuthorIDsFilterInput() : ExcludeAuthorIDsFilterInput
    {
        /** @var ExcludeAuthorIDsFilterInput */
        return $this->excludeAuthorIDsFilterInput = $this->excludeAuthorIDsFilterInput ?? $this->instanceManager->getInstance(ExcludeAuthorIDsFilterInput::class);
    }
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'getInputFieldDescription']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_TYPE_MODIFIERS, \Closure::fromCallable([$this, 'getInputFieldTypeModifiers']), 10, 3);
        App::addFilter(HookNames::INPUT_FIELD_FILTER_INPUT, \Closure::fromCallable([$this, 'getInputFieldFilterInput']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldNameTypeResolvers;
        }
        return \array_merge($inputFieldNameTypeResolvers, ['authorIDs' => $this->getIDScalarTypeResolver(), 'excludeAuthorIDs' => $this->getIDScalarTypeResolver(), 'customPostAuthorIDs' => $this->getIDScalarTypeResolver(), 'excludeCustomPostAuthorIDs' => $this->getIDScalarTypeResolver()]);
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
                return $this->__('Filter comments from the authors with given IDs', 'comments');
            case 'excludeAuthorIDs':
                return $this->__('Exclude comments from authors with given IDs', 'comments');
            case 'customPostAuthorIDs':
                return $this->__('Filter comments added to custom posts from the authors with given IDs', 'comments');
            case 'excludeCustomPostAuthorIDs':
                return $this->__('Exclude comments added to custom posts from authors with given IDs', 'comments');
            default:
                return $inputFieldDescription;
        }
    }
    /**
     * @param int $inputFieldTypeModifiers
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldTypeModifiers($inputFieldTypeModifiers, $inputObjectTypeResolver, $inputFieldName) : int
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldTypeModifiers;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
            case 'excludeAuthorIDs':
            case 'customPostAuthorIDs':
            case 'excludeCustomPostAuthorIDs':
                return SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
            default:
                return $inputFieldTypeModifiers;
        }
    }
    /**
     * @param \PoP\ComponentModel\FilterInputs\FilterInputInterface|null $inputFieldFilterInput
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldFilterInput($inputFieldFilterInput, $inputObjectTypeResolver, $inputFieldName) : ?FilterInputInterface
    {
        if (!$inputObjectTypeResolver instanceof RootCommentsFilterInputObjectTypeResolver) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
                return $this->getAuthorIDsFilterInput();
            case 'excludeAuthorIDs':
                return $this->getExcludeAuthorIDsFilterInput();
            case 'customPostAuthorIDs':
                return $this->getCustomPostAuthorIDsFilterInput();
            case 'excludeCustomPostAuthorIDs':
                return $this->getExcludeCustomPostAuthorIDsFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
