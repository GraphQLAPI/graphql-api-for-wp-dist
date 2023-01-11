<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\CreateCustomPostFilterInputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType\UpdateCustomPostFilterInputObjectTypeResolverInterface;
abstract class AbstractCustomPostMutationResolverHookSet extends AbstractHookSet
{
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface|null
     */
    private $customPostMediaTypeMutationAPI;
    /**
     * @var \PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver|null
     */
    private $idScalarTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI
     */
    public final function setCustomPostMediaTypeMutationAPI($customPostMediaTypeMutationAPI) : void
    {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }
    protected final function getCustomPostMediaTypeMutationAPI() : CustomPostMediaTypeMutationAPIInterface
    {
        /** @var CustomPostMediaTypeMutationAPIInterface */
        return $this->customPostMediaTypeMutationAPI = $this->customPostMediaTypeMutationAPI ?? $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    }
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
    protected function init() : void
    {
        App::addFilter(HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS, \Closure::fromCallable([$this, 'maybeAddInputFieldNameTypeResolvers']), 10, 2);
        App::addFilter(HookNames::INPUT_FIELD_DESCRIPTION, \Closure::fromCallable([$this, 'maybeAddInputFieldDescription']), 10, 3);
    }
    /**
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function maybeAddInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver) : array
    {
        // Only for the specific combinations of Type and fieldName
        if (!$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        $inputFieldNameTypeResolvers[MutationInputProperties::FEATUREDIMAGE_ID] = $this->getIDScalarTypeResolver();
        return $inputFieldNameTypeResolvers;
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver) : bool
    {
        return $inputObjectTypeResolver instanceof CreateCustomPostFilterInputObjectTypeResolverInterface || $inputObjectTypeResolver instanceof UpdateCustomPostFilterInputObjectTypeResolverInterface;
    }
    /**
     * @param string|null $inputFieldDescription
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     * @param string $inputFieldName
     */
    public function maybeAddInputFieldDescription($inputFieldDescription, $inputObjectTypeResolver, $inputFieldName) : ?string
    {
        // Only for the newly added inputFieldName
        if ($inputFieldName !== MutationInputProperties::FEATUREDIMAGE_ID || !$this->isInputObjectTypeResolver($inputObjectTypeResolver)) {
            return $inputFieldDescription;
        }
        return $this->__('The ID of the image to set as featured', 'custompost-mutations');
    }
}
