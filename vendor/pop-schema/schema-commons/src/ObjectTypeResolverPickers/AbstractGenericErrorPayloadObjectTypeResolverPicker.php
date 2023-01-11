<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectTypeResolverPickers;

use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
abstract class AbstractGenericErrorPayloadObjectTypeResolverPicker extends \PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker implements \PoPSchema\SchemaCommons\ObjectTypeResolverPickers\GenericErrorPayloadObjectTypeResolverPickerInterface
{
    /**
     * @var \PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver|null
     */
    private $genericErrorPayloadObjectTypeResolver;
    /**
     * @param \PoPSchema\SchemaCommons\TypeResolvers\ObjectType\GenericErrorPayloadObjectTypeResolver $genericErrorPayloadObjectTypeResolver
     */
    public final function setGenericErrorPayloadObjectTypeResolver($genericErrorPayloadObjectTypeResolver) : void
    {
        $this->genericErrorPayloadObjectTypeResolver = $genericErrorPayloadObjectTypeResolver;
    }
    protected final function getGenericErrorPayloadObjectTypeResolver() : GenericErrorPayloadObjectTypeResolver
    {
        /** @var GenericErrorPayloadObjectTypeResolver */
        return $this->genericErrorPayloadObjectTypeResolver = $this->genericErrorPayloadObjectTypeResolver ?? $this->instanceManager->getInstance(GenericErrorPayloadObjectTypeResolver::class);
    }
    public function getObjectTypeResolver() : ObjectTypeResolverInterface
    {
        return $this->getGenericErrorPayloadObjectTypeResolver();
    }
    protected function getTargetObjectClass() : string
    {
        return GenericErrorPayload::class;
    }
}
