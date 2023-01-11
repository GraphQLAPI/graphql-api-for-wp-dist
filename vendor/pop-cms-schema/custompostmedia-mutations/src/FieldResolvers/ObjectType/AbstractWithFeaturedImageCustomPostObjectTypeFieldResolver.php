<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\Module;
use PoPCMSSchema\CustomPostMediaMutations\ModuleConfiguration;
use PoPCMSSchema\CustomPostMediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\RemoveFeaturedImageFromCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\SetFeaturedImageOnCustomPostMutationResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\CustomPostSetFeaturedImageFilterInputObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoP\ComponentModel\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType\MaybeWithFeaturedImageCustomPostObjectTypeFieldResolverTrait;
use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
use stdClass;
abstract class AbstractWithFeaturedImageCustomPostObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    use MaybeWithFeaturedImageCustomPostObjectTypeFieldResolverTrait;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver|null
     */
    private $mediaObjectTypeResolver;
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
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\CustomPostSetFeaturedImageFilterInputObjectTypeResolver|null
     */
    private $customPostSetFeaturedImageFilterInputObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableSetFeaturedImageOnCustomPostMutationResolver|null
     */
    private $payloadableSetFeaturedImageOnCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\PayloadableRemoveFeaturedImageFromCustomPostMutationResolver|null
     */
    private $payloadableRemoveFeaturedImageFromCustomPostMutationResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver|null
     */
    private $customPostSetFeaturedImageMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver|null
     */
    private $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface|null
     */
    private $customPostMediaTypeAPI;
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver $mediaObjectTypeResolver
     */
    public final function setMediaObjectTypeResolver($mediaObjectTypeResolver) : void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    protected final function getMediaObjectTypeResolver() : MediaObjectTypeResolver
    {
        /** @var MediaObjectTypeResolver */
        return $this->mediaObjectTypeResolver = $this->mediaObjectTypeResolver ?? $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
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
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\InputObjectType\CustomPostSetFeaturedImageFilterInputObjectTypeResolver $customPostSetFeaturedImageFilterInputObjectTypeResolver
     */
    public final function setCustomPostSetFeaturedImageFilterInputObjectTypeResolver($customPostSetFeaturedImageFilterInputObjectTypeResolver) : void
    {
        $this->customPostSetFeaturedImageFilterInputObjectTypeResolver = $customPostSetFeaturedImageFilterInputObjectTypeResolver;
    }
    protected final function getCustomPostSetFeaturedImageFilterInputObjectTypeResolver() : CustomPostSetFeaturedImageFilterInputObjectTypeResolver
    {
        /** @var CustomPostSetFeaturedImageFilterInputObjectTypeResolver */
        return $this->customPostSetFeaturedImageFilterInputObjectTypeResolver = $this->customPostSetFeaturedImageFilterInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostSetFeaturedImageFilterInputObjectTypeResolver::class);
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
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver $customPostSetFeaturedImageMutationPayloadObjectTypeResolver
     */
    public final function setCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver($customPostSetFeaturedImageMutationPayloadObjectTypeResolver) : void
    {
        $this->customPostSetFeaturedImageMutationPayloadObjectTypeResolver = $customPostSetFeaturedImageMutationPayloadObjectTypeResolver;
    }
    protected final function getCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver() : CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver
    {
        /** @var CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver */
        return $this->customPostSetFeaturedImageMutationPayloadObjectTypeResolver = $this->customPostSetFeaturedImageMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostSetFeaturedImageMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver
     */
    public final function setCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver($customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver) : void
    {
        $this->customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver = $customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver;
    }
    protected final function getCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver() : CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver
    {
        /** @var CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver */
        return $this->customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver = $this->customPostRemoveFeaturedImageMutationPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface $customPostMediaTypeAPI
     */
    public final function setCustomPostMediaTypeAPI($customPostMediaTypeAPI) : void
    {
        $this->customPostMediaTypeAPI = $customPostMediaTypeAPI;
    }
    protected final function getCustomPostMediaTypeAPI() : CustomPostMediaTypeAPIInterface
    {
        /** @var CustomPostMediaTypeAPIInterface */
        return $this->customPostMediaTypeAPI = $this->customPostMediaTypeAPI ?? $this->instanceManager->getInstance(CustomPostMediaTypeAPIInterface::class);
    }
    /**
     * @return string[]
     */
    public function getFieldNamesToResolve() : array
    {
        return ['setFeaturedImage', 'removeFeaturedImage'];
    }
    /**
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function getFieldDescription($objectTypeResolver, $fieldName) : ?string
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
                return $this->__('Set the featured image on the custom post', 'custompostmedia-mutations');
            case 'removeFeaturedImage':
                return $this->__('Remove the featured image on the custom post', 'custompostmedia-mutations');
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
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
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
            case 'setFeaturedImage':
                return [MutationInputProperties::INPUT => $this->getCustomPostSetFeaturedImageFilterInputObjectTypeResolver()];
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
        switch ([$fieldName => $fieldArgName]) {
            case ['setFeaturedImage' => MutationInputProperties::INPUT]:
                return SchemaTypeModifiers::MANDATORY;
            default:
                return parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName);
        }
    }
    /**
     * Validated the mutation on the object because the ID
     * is obtained from the same object, so it's not originally
     * present in the field argument in the query
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param string $fieldName
     */
    public function validateMutationOnObject($objectTypeResolver, $fieldName) : bool
    {
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return \true;
            default:
                return parent::validateMutationOnObject($objectTypeResolver, $fieldName);
        }
    }
    /**
     * @param array<string,mixed> $fieldArgsForMutationForObject
     * @return array<string,mixed>
     * @param object $object
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object) : array
    {
        $fieldArgsForMutationForObject = parent::prepareFieldArgsForMutationForObject($fieldArgsForMutationForObject, $objectTypeResolver, $field, $object);
        $customPost = $object;
        switch ($field->getName()) {
            case 'removeFeaturedImage':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT] = $fieldArgsForMutationForObject[MutationInputProperties::INPUT] ?? new stdClass();
                break;
        }
        switch ($field->getName()) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                $fieldArgsForMutationForObject[MutationInputProperties::INPUT]->{MutationInputProperties::CUSTOMPOST_ID} = $objectTypeResolver->getID($customPost);
                break;
        }
        return $fieldArgsForMutationForObject;
    }
    /**
     * Because "removeFeaturedImage" receives no arguments, it doesn't
     * know it needs to pass the "input" entry to the MutationResolver,
     * so explicitly set it up then.
     * @param \PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface $objectTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getFieldArgsInputObjectSubpropertyName($objectTypeResolver, $field) : ?string
    {
        switch ($field->getName()) {
            case 'removeFeaturedImage':
                return MutationInputProperties::INPUT;
            default:
                return parent::getFieldArgsInputObjectSubpropertyName($objectTypeResolver, $field);
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
            case 'setFeaturedImage':
                return $usePayloadableCustomPostMediaMutations ? $this->getPayloadableSetFeaturedImageOnCustomPostMutationResolver() : $this->getSetFeaturedImageOnCustomPostMutationResolver();
            case 'removeFeaturedImage':
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
                case 'setFeaturedImage':
                    return $this->getCustomPostSetFeaturedImageMutationPayloadObjectTypeResolver();
                case 'removeFeaturedImage':
                    return $this->getCustomPostRemoveFeaturedImageMutationPayloadObjectTypeResolver();
                default:
                    return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
            }
        }
        switch ($fieldName) {
            case 'setFeaturedImage':
            case 'removeFeaturedImage':
                return $this->getCustomPostUnionTypeResolver();
            default:
                return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
        }
    }
}
