<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\ObjectModels\CustomPostDoesNotExistErrorPayload;
use PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractCustomPostDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    /**
     * @var \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeResolver|null
     */
    private $customPostDoesNotExistErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPCMSSchema\CustomPostMutations\TypeResolvers\ObjectType\CustomPostDoesNotExistErrorPayloadObjectTypeResolver $customPostDoesNotExistErrorPayloadObjectTypeResolver
     */
    public final function setCustomPostDoesNotExistErrorPayloadObjectTypeResolver($customPostDoesNotExistErrorPayloadObjectTypeResolver) : void
    {
        $this->customPostDoesNotExistErrorPayloadObjectTypeResolver = $customPostDoesNotExistErrorPayloadObjectTypeResolver;
    }
    protected final function getCustomPostDoesNotExistErrorPayloadObjectTypeResolver() : CustomPostDoesNotExistErrorPayloadObjectTypeResolver
    {
        /** @var CustomPostDoesNotExistErrorPayloadObjectTypeResolver */
        return $this->customPostDoesNotExistErrorPayloadObjectTypeResolver = $this->customPostDoesNotExistErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostDoesNotExistErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getCustomPostDoesNotExistErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return CustomPostDoesNotExistErrorPayload::class;
    }
}
