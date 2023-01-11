<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentFilterInputObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
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
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver|null
     */
    private $addCommentToCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostFilterInputObjectTypeResolver|null
     */
    private $rootAddCommentToCustomPostFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentFilterInputObjectTypeResolver|null
     */
    private $rootReplyCommentFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver|null
     */
    private $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver|null
     */
    private $rootReplyCommentMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver|null
     */
    private $payloadableAddCommentToCustomPostMutationResolver;
    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver $commentObjectTypeResolver
     */
    public final function setCommentObjectTypeResolver($commentObjectTypeResolver) : void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    protected final function getCommentObjectTypeResolver() : CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver = $this->commentObjectTypeResolver ?? $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver
     */
    public final function setAddCommentToCustomPostMutationResolver($addCommentToCustomPostMutationResolver) : void
    {
        $this->addCommentToCustomPostMutationResolver = $addCommentToCustomPostMutationResolver;
    }
    protected final function getAddCommentToCustomPostMutationResolver() : AddCommentToCustomPostMutationResolver
    {
        /** @var AddCommentToCustomPostMutationResolver */
        return $this->addCommentToCustomPostMutationResolver = $this->addCommentToCustomPostMutationResolver ?? $this->instanceManager->getInstance(AddCommentToCustomPostMutationResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootAddCommentToCustomPostFilterInputObjectTypeResolver $rootAddCommentToCustomPostFilterInputObjectTypeResolver
     */
    public final function setRootAddCommentToCustomPostFilterInputObjectTypeResolver($rootAddCommentToCustomPostFilterInputObjectTypeResolver) : void
    {
        $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver = $rootAddCommentToCustomPostFilterInputObjectTypeResolver;
    }
    protected final function getRootAddCommentToCustomPostFilterInputObjectTypeResolver() : RootAddCommentToCustomPostFilterInputObjectTypeResolver
    {
        /** @var RootAddCommentToCustomPostFilterInputObjectTypeResolver */
        return $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver = $this->rootAddCommentToCustomPostFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootAddCommentToCustomPostFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\InputObjectType\RootReplyCommentFilterInputObjectTypeResolver $rootReplyCommentFilterInputObjectTypeResolver
     */
    public final function setRootReplyCommentFilterInputObjectTypeResolver($rootReplyCommentFilterInputObjectTypeResolver) : void
    {
        $this->rootReplyCommentFilterInputObjectTypeResolver = $rootReplyCommentFilterInputObjectTypeResolver;
    }
    protected final function getRootReplyCommentFilterInputObjectTypeResolver() : RootReplyCommentFilterInputObjectTypeResolver
    {
        /** @var RootReplyCommentFilterInputObjectTypeResolver */
        return $this->rootReplyCommentFilterInputObjectTypeResolver = $this->rootReplyCommentFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(RootReplyCommentFilterInputObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootAddCommentToCustomPostMutationPayloadObjectTypeResolver $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver
     */
    public final function setRootAddCommentToCustomPostMutationPayloadObjectTypeResolver($rootAddCommentToCustomPostMutationPayloadObjectTypeResolver) : void
    {
        $this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver = $rootAddCommentToCustomPostMutationPayloadObjectTypeResolver;
    }
    protected final function getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver() : RootAddCommentToCustomPostMutationPayloadObjectTypeResolver
    {
        /** @var RootAddCommentToCustomPostMutationPayloadObjectTypeResolver */
        return $this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver = $this->rootAddCommentToCustomPostMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootAddCommentToCustomPostMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\RootReplyCommentMutationPayloadObjectTypeResolver $rootReplyCommentMutationPayloadObjectTypeResolver
     */
    public final function setRootReplyCommentMutationPayloadObjectTypeResolver($rootReplyCommentMutationPayloadObjectTypeResolver) : void
    {
        $this->rootReplyCommentMutationPayloadObjectTypeResolver = $rootReplyCommentMutationPayloadObjectTypeResolver;
    }
    protected final function getRootReplyCommentMutationPayloadObjectTypeResolver() : RootReplyCommentMutationPayloadObjectTypeResolver
    {
        /** @var RootReplyCommentMutationPayloadObjectTypeResolver */
        return $this->rootReplyCommentMutationPayloadObjectTypeResolver = $this->rootReplyCommentMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(RootReplyCommentMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CommentMutations\MutationResolvers\PayloadableAddCommentToCustomPostMutationResolver $payloadableAddCommentToCustomPostMutationResolver
     */
    public final function setPayloadableAddCommentToCustomPostMutationResolver($payloadableAddCommentToCustomPostMutationResolver) : void
    {
        $this->payloadableAddCommentToCustomPostMutationResolver = $payloadableAddCommentToCustomPostMutationResolver;
    }
    protected final function getPayloadableAddCommentToCustomPostMutationResolver() : PayloadableAddCommentToCustomPostMutationResolver
    {
        /** @var PayloadableAddCommentToCustomPostMutationResolver */
        return $this->payloadableAddCommentToCustomPostMutationResolver = $this->payloadableAddCommentToCustomPostMutationResolver ?? $this->instanceManager->getInstance(PayloadableAddCommentToCustomPostMutationResolver::class);
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
        return ['addCommentToCustomPost', 'replyComment'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
                return $this->__('Add a comment to a custom post', 'comment-mutations');
            case 'replyComment':
                return $this->__('Reply a comment with another comment', 'comment-mutations');
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
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if (!$usePayloadableCommentMutations) {
            return parent::getFieldTypeModifiers($objectTypeResolver, $fieldName);
        }
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
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
            case 'addCommentToCustomPost':
                return [MutationInputProperties::INPUT => $this->getRootAddCommentToCustomPostFilterInputObjectTypeResolver()];
            case 'replyComment':
                return [MutationInputProperties::INPUT => $this->getRootReplyCommentFilterInputObjectTypeResolver()];
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
            case MutationInputProperties::INPUT:
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
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return $usePayloadableCommentMutations ? $this->getPayloadableAddCommentToCustomPostMutationResolver() : $this->getAddCommentToCustomPostMutationResolver();
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
        $usePayloadableCommentMutations = $moduleConfiguration->usePayloadableCommentMutations();
        if ($usePayloadableCommentMutations) {
            switch ($fieldName) {
                case 'addCommentToCustomPost':
                    return $this->getRootAddCommentToCustomPostMutationPayloadObjectTypeResolver();
                case 'replyComment':
                    return $this->getRootReplyCommentMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return $this->getCommentObjectTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
