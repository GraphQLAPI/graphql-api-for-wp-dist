<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\SchemaHooks;

use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput;
use PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\ExcludeAuthorIDsFilterInput;
abstract class AbstractAddAuthorInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    use \PoPCMSSchema\Users\SchemaHooks\AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver|null
     */
    private $stringScalarTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorIDsFilterInput|null
     */
    private $authorIDsFilterInput;
    /**
     * @var \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput|null
     */
    private $authorSlugFilterInput;
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
     * @param \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver $stringScalarTypeResolver
     */
    public final function setStringScalarTypeResolver($stringScalarTypeResolver) : void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    protected final function getStringScalarTypeResolver() : StringScalarTypeResolver
    {
        /** @var StringScalarTypeResolver */
        return $this->stringScalarTypeResolver = $this->stringScalarTypeResolver ?? $this->instanceManager->getInstance(StringScalarTypeResolver::class);
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
     * @param \PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\FilterInputs\AuthorSlugFilterInput $authorSlugFilterInput
     */
    public final function setAuthorSlugFilterInput($authorSlugFilterInput) : void
    {
        $this->authorSlugFilterInput = $authorSlugFilterInput;
    }
    protected final function getAuthorSlugFilterInput() : AuthorSlugFilterInput
    {
        /** @var AuthorSlugFilterInput */
        return $this->authorSlugFilterInput = $this->authorSlugFilterInput ?? $this->instanceManager->getInstance(AuthorSlugFilterInput::class);
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
     * Indicate if to add the fields added by the SchemaHookSet
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected abstract function addAuthorInputFields($inputObjectTypeResolver) : bool;
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>|mixed[]
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        return \array_merge($inputFieldNameTypeResolvers, $this->getAuthorInputFieldNameTypeResolvers());
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function getInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
                return $this->__('Get results from the authors with given IDs', 'pop-users');
            case 'authorSlug':
                return $this->__('Get results from the authors with given slug', 'pop-users');
            case 'excludeAuthorIDs':
                return $this->__('Get results excluding the ones from authors with given IDs', 'pop-users');
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
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldTypeModifiers;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
            case 'excludeAuthorIDs':
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
        if (!$this->addAuthorInputFields($inputObjectTypeResolver)) {
            return $inputFieldFilterInput;
        }
        switch ($inputFieldName) {
            case 'authorIDs':
                return $this->getAuthorIDsFilterInput();
            case 'authorSlug':
                return $this->getAuthorSlugFilterInput();
            case 'excludeAuthorIDs':
                return $this->getExcludeAuthorIDsFilterInput();
            default:
                return $inputFieldFilterInput;
        }
    }
}
