<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMutations\Module as CustomPostMutationsModule;
use PoPCMSSchema\CustomPostMutations\ModuleConfiguration as CustomPostMutationsModuleConfiguration;
use PoPCMSSchema\PostMutations\MutationResolvers\CreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootCreatePostFilterInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootUpdatePostFilterInputObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint;
use PoP\ComponentModel\Checkpoints\CheckpointInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Module as EngineModule;
use PoP\Engine\ModuleConfiguration as EngineModuleConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Root\App;
class RootObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver|null
     */
    private $rootUpdatePostMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver|null
     */
    private $rootCreatePostMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\MutationResolvers\CreatePostMutationResolver|null
     */
    private $createPostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver|null
     */
    private $updatePostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver|null
     */
    private $payloadableUpdatePostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostMutationResolver|null
     */
    private $payloadableCreatePostMutationResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootUpdatePostFilterInputObjectTypeResolver|null
     */
    private $rootUpdatePostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootCreatePostFilterInputObjectTypeResolver|null
     */
    private $rootCreatePostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint|null
     */
    private $userLoggedInCheckpoint;
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver $postObjectTypeResolver
     */
    public final function setPostObjectTypeResolver($postObjectTypeResolver) : void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    protected final function getPostObjectTypeResolver() : PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver = $this->postObjectTypeResolver ?? $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootUpdatePostMutationPayloadObjectTypeResolver $rootUpdatePostMutationPayloadObjectTypeResolver
     */
    public final function setRootUpdatePostMutationPayloadObjectTypeResolver($rootUpdatePostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootUpdatePostMutationPayloadObjectTypeResolver = $rootUpdatePostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootUpdatePostMutationPayloadObjectTypeResolver() : RootUpdatePostMutationPayloadObjectTypeResolver
    {
        /** @var RootUpdatePostMutationPayloadObjectTypeResolver */
        return $this->rootUpdatePostMutationPayloadObjectTypeResolver = $this->rootUpdatePostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootUpdatePostMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\ObjectType\RootCreatePostMutationPayloadObjectTypeResolver $rootCreatePostMutationPayloadObjectTypeResolver
     */
    public final function setRootCreatePostMutationPayloadObjectTypeResolver($rootCreatePostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootCreatePostMutationPayloadObjectTypeResolver = $rootCreatePostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootCreatePostMutationPayloadObjectTypeResolver() : RootCreatePostMutationPayloadObjectTypeResolver
    {
        /** @var RootCreatePostMutationPayloadObjectTypeResolver */
        return $this->rootCreatePostMutationPayloadObjectTypeResolver = $this->rootCreatePostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootCreatePostMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\MutationResolvers\CreatePostMutationResolver $createPostMutationResolver
     */
    public final function setCreatePostMutationResolver($createPostMutationResolver) : void
    {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }
    protected final function getCreatePostMutationResolver() : CreatePostMutationResolver
    {
        /** @var CreatePostMutationResolver */
        return $this->createPostMutationResolver = $this->createPostMutationResolver ?? $this->instanceManager->getInstance(CreatePostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\MutationResolvers\UpdatePostMutationResolver $updatePostMutationResolver
     */
    public final function setUpdatePostMutationResolver($updatePostMutationResolver) : void
    {
        $this->updatePostMutationResolver = $updatePostMutationResolver;
    }
    protected final function getUpdatePostMutationResolver() : UpdatePostMutationResolver
    {
        /** @var UpdatePostMutationResolver */
        return $this->updatePostMutationResolver = $this->updatePostMutationResolver ?? $this->instanceManager->getInstance(UpdatePostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\MutationResolvers\PayloadableUpdatePostMutationResolver $payloadableUpdatePostMutationResolver
     */
    public final function setPayloadableUpdatePostMutationResolver($payloadableUpdatePostMutationResolver) : void
    {
        $this->payloadableUpdatePostMutationResolver = $payloadableUpdatePostMutationResolver;
    }
    protected final function getPayloadableUpdatePostMutationResolver() : PayloadableUpdatePostMutationResolver
    {
        /** @var PayloadableUpdatePostMutationResolver */
        return $this->payloadableUpdatePostMutationResolver = $this->payloadableUpdatePostMutationResolver ?? $this->instanceManager->getInstance(PayloadableUpdatePostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\MutationResolvers\PayloadableCreatePostMutationResolver $payloadableCreatePostMutationResolver
     */
    public final function setPayloadableCreatePostMutationResolver($payloadableCreatePostMutationResolver) : void
    {
        $this->payloadableCreatePostMutationResolver = $payloadableCreatePostMutationResolver;
    }
    protected final function getPayloadableCreatePostMutationResolver() : PayloadableCreatePostMutationResolver
    {
        /** @var PayloadableCreatePostMutationResolver */
        return $this->payloadableCreatePostMutationResolver = $this->payloadableCreatePostMutationResolver ?? $this->instanceManager->getInstance(PayloadableCreatePostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootUpdatePostFilterInputObjectTypeResolver $rootUpdatePostFilterInputObjectTypeResolver
     */
    public final function setRootUpdatePostFilterInputObjectTypeResolver($rootUpdatePostFilterInputObjectTypeResolver) : void
    {
        $this->rootUpdatePostFilterInputObjectTypeResolver = $rootUpdatePostFilterInputObjectTypeResolver;
    }
    protected final function getRootUpdatePostFilterInputObjectTypeResolver() : RootUpdatePostFilterInputObjectTypeResolver
    {
        /** @var RootUpdatePostFilterInputObjectTypeResolver */
        return $this->rootUpdatePostFilterInputObjectTypeResolver = $this->rootUpdatePostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootUpdatePostFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostMutations\TypeResolvers\InputObjectType\RootCreatePostFilterInputObjectTypeResolver $rootCreatePostFilterInputObjectTypeResolver
     */
    public final function setRootCreatePostFilterInputObjectTypeResolver($rootCreatePostFilterInputObjectTypeResolver) : void
    {
        $this->rootCreatePostFilterInputObjectTypeResolver = $rootCreatePostFilterInputObjectTypeResolver;
    }
    protected final function getRootCreatePostFilterInputObjectTypeResolver() : RootCreatePostFilterInputObjectTypeResolver
    {
        /** @var RootCreatePostFilterInputObjectTypeResolver */
        return $this->rootCreatePostFilterInputObjectTypeResolver = $this->rootCreatePostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootCreatePostFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserState\Checkpoints\UserLoggedInCheckpoint $userLoggedInCheckpoint
     */
    public final function setUserLoggedInCheckpoint($userLoggedInCheckpoint) : void
    {
        $this->userLoggedInCheckpoint = $userLoggedInCheckpoint;
    }
    protected final function getUserLoggedInCheckpoint() : UserLoggedInCheckpoint
    {
        /** @var UserLoggedInCheckpoint */
        return $this->userLoggedInCheckpoint = $this->userLoggedInCheckpoint ?? $this->instanceManager->getInstance(UserLoggedInCheckpoint::class);
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
        return \array_merge(['createPost'], !$moduleConfiguration->disableRedundantRootTypeMutationFields() ? ['updatePost'] : []);
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'createPost':
                return $this->__('Create a post', 'post-mutations');
            case 'updatePost':
                return $this->__('Update a post', 'post-mutations');
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
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if (!$usePayloadableCustomPostMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
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
            case 'createPost':
                return ['input' => $this->getRootCreatePostFilterInputObjectTypeResolver()];
            case 'updatePost':
                return ['input' => $this->getRootUpdatePostFilterInputObjectTypeResolver()];
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
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        switch ($fieldName) {
            case 'createPost':
                return $usePayloadableCustomPostMutations ? $this->getPayloadableCreatePostMutationResolver() : $this->getCreatePostMutationResolver();
            case 'updatePost':
                return $usePayloadableCustomPostMutations ? $this->getPayloadableUpdatePostMutationResolver() : $this->getUpdatePostMutationResolver();
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
        /** @var CustomPostMutationsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            switch ($fieldName) {
                case 'createPost':
                    return $this->getRootCreatePostMutationPayloadObjectTypeResolver();
                case 'updatePost':
                    return $this->getRootUpdatePostMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'createPost':
            case 'updatePost':
                return $this->getPostObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @return CheckpointInterface[]
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor
     */
    public function getValidationCheckpoints($objectTypeResolver, $fieldDataAccessor, $object) : array
    {
        $validationCheckpoints = parent::getValidationCheckpoints($objectTypeResolver, $fieldDataAccessor, $object);
        /**
         * For Payloadable: The "User Logged-in" checkpoint validation is not added,
         * instead this validation is executed inside the mutation, so the error
         * shows up in the Payload
         *
         * @var CustomPostMutationsModuleConfiguration
         */
        $moduleConfiguration = App::getModule(CustomPostMutationsModule::class)->getConfiguration();
        $usePayloadableCustomPostMutations = $moduleConfiguration->usePayloadableCustomPostMutations();
        if ($usePayloadableCustomPostMutations) {
            return $validationCheckpoints;
        }
        switch ($fieldDataAccessor->getFieldName()) {
            case 'createPost':
            case 'updatePost':
                $validationCheckpoints[] = $this->getUserLoggedInCheckpoint();
                break;
        }
        return $validationCheckpoints;
    }
}
