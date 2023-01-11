<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Module;
use PoPCMSSchema\CustomPostMediaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver|null
     */
    private $customPostUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver|null
     */
    private $setFeaturedImageOnCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver|null
     */
    private $removeFeaturedImageFromCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver|null
     */
    private $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver|null
     */
    private $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver|null
     */
    private $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver|null
     */
    private $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver|null
     */
    private $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver|null
     */
    private $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver $customPostUnionTypeResolver
     */
    public final function setCustomPostUnionTypeResolver($customPostUnionTypeResolver) : void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    protected final function getCustomPostUnionTypeResolver() : CustomPostUnionTypeResolver
    {
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver = $this->customPostUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver $setFeaturedImageOnCustomPostMutationResolver
     */
    public final function setSetFeaturedImageOnCustomPostMutationResolver($setFeaturedImageOnCustomPostMutationResolver) : void
    {
        $this->setFeaturedImageOnCustomPostMutationResolver = $setFeaturedImageOnCustomPostMutationResolver;
    }
    protected final function getSetFeaturedImageOnCustomPostMutationResolver() : SetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var SetFeaturedImageOnCustomPostMutationResolver */
        return $this->setFeaturedImageOnCustomPostMutationResolver = $this->setFeaturedImageOnCustomPostMutationResolver ?? $this->instanceManager->getInstance(SetFeaturedImageOnCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver $removeFeaturedImageFromCustomPostMutationResolver
     */
    public final function setRemoveFeaturedImageFromCustomPostMutationResolver($removeFeaturedImageFromCustomPostMutationResolver) : void
    {
        $this->removeFeaturedImageFromCustomPostMutationResolver = $removeFeaturedImageFromCustomPostMutationResolver;
    }
    protected final function getRemoveFeaturedImageFromCustomPostMutationResolver() : RemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var RemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->removeFeaturedImageFromCustomPostMutationResolver = $this->removeFeaturedImageFromCustomPostMutationResolver ?? $this->instanceManager->getInstance(RemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
     */
    public final function setRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver($rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver) : void
    {
        $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = $rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver;
    }
    protected final function getRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver() : RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver = $this->rootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver
     */
    public final function setRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver($rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver) : void
    {
        $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver;
    }
    protected final function getRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver() : RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver = $this->rootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver $payloadableSetFeaturedImageOnCustomPostMutationResolver
     */
    public final function setPayloadableSetFeaturedImageOnCustomPostMutationResolver($payloadableSetFeaturedImageOnCustomPostMutationResolver) : void
    {
        $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    }
    protected final function getPayloadableSetFeaturedImageOnCustomPostMutationResolver() : PayloadableSetFeaturedImageOnCustomPostMutationResolver
    {
        /** @var PayloadableSetFeaturedImageOnCustomPostMutationResolver */
        return $this->payloadableSetFeaturedImageOnCustomPostMutationResolver = $this->payloadableSetFeaturedImageOnCustomPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableSetFeaturedImageOnCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver $payloadableRemoveFeaturedImageFromCustomPostMutationResolver
     */
    public final function setPayloadableRemoveFeaturedImageFromCustomPostMutationResolver($payloadableRemoveFeaturedImageFromCustomPostMutationResolver) : void
    {
        $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    }
    protected final function getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver() : PayloadableRemoveFeaturedImageFromCustomPostMutationResolver
    {
        /** @var PayloadableRemoveFeaturedImageFromCustomPostMutationResolver */
        return $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver = $this->payloadableRemoveFeaturedImageFromCustomPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableRemoveFeaturedImageFromCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver
     */
    public final function setRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver($rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver() : RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver */
        return $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver = $this->rootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver
     */
    public final function setRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver($rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver() : RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver */
        return $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver = $this->rootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo() : array
    {
        return [RootObjectTypeResolver::class];
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        /** @var EngineModuleConfiguration */
        $moduleConfiguration = App::getModule(EngineModule::class)->getConfiguration();
        if ($moduleConfiguration->disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return ['setFeaturedImageOnCustomPost', 'removeFeaturedImageFromCustomPost'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return $this->__('Set the featured image on a custom post', 'custompostmedia-mutations');
            case 'removeFeaturedImageFromCustomPost':
                return $this->__('Remove the featured image from a custom post', 'custompostmedia-mutations');
            default:
                return parent::getFieldDescription($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeModifiers($objectTypeResolver, $fieldName) : int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        if (!$usePayloadableCustomPostMediaMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return SchemaTypeModifiers::NON_NULLABLE;
            default:
                return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName) : array
    {
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return ['input' => $this->getRootSetFeaturedImageOnCustomPostFilterInputObjectTypeResolver()];
            case 'removeFeaturedImageFromCustomPost':
                return ['input' => $this->getRootRemoveFeaturedImageFromCustomPostFilterInputObjectTypeResolver()];
            default:
                return parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     * @param string $fieldArgName
     */
    public function getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName) : int
    {
        switch ($fieldArgName) {
            case 'input':
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldMutationResolver($objectTypeResolver, $fieldName) : ?MutationResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
                return $usePayloadableCustomPostMediaMutations ? $this->getPayloadableSetFeaturedImageOnCustomPostMutationResolver() : $this->getSetFeaturedImageOnCustomPostMutationResolver();
            case 'removeFeaturedImageFromCustomPost':
                return $usePayloadableCustomPostMediaMutations ? $this->getPayloadableRemoveFeaturedImageFromCustomPostMutationResolver() : $this->getRemoveFeaturedImageFromCustomPostMutationResolver();
            default:
                return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldTypeResolver($objectTypeResolver, $fieldName) : ConcreteTypeResolverInterface
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $usePayloadableCustomPostMediaMutations = $moduleConfiguration->usePayloadableCustomPostMediaMutations();
        if ($usePayloadableCustomPostMediaMutations) {
            switch ($fieldName) {
                case 'setFeaturedImageOnCustomPost':
                    return $this->getRootSetFeaturedImageOnCustomPostMutationPayloadObjectTypeResolver();
                case 'removeFeaturedImageFromCustomPost':
                    return $this->getRootRemoveFeaturedImageFromCustomPostMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'setFeaturedImageOnCustomPost':
            case 'removeFeaturedImageFromCustomPost':
                return $this->getCustomPostUnionTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
