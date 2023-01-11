<?php

declare (strict_types=1);
namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Services\BasicServiceTrait;
class DataloadHelperService implements \PoP\ComponentModel\HelperServices\DataloadHelperServiceInterface
{
    use BasicServiceTrait;
    /**
     * @var \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface|null
     */
    private $componentProcessorManager;
    /**
     * @param \PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface $componentProcessorManager
     */
    public final function setComponentProcessorManager($componentProcessorManager) : void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    protected final function getComponentProcessorManager() : ComponentProcessorManagerInterface
    {
        /** @var ComponentProcessorManagerInterface */
        return $this->componentProcessorManager = $this->componentProcessorManager ?? $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }
    /**
     * Accept RelationalTypeResolverInterface as param, instead of the more natural
     * ObjectTypeResolverInterface, to make it easy within the application to check
     * for this result without checking in advance what's the typeResolver.
     * @param \PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface $relationalTypeResolver
     * @param \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field
     */
    public function getTypeResolverFromSubcomponentField($relationalTypeResolver, $field) : ?RelationalTypeResolverInterface
    {
        /**
         * Because the UnionTypeResolver doesn't know yet which TypeResolver will be used
         * (that depends on each object), it can't resolve this functionality
         */
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            return null;
        }
        // By now, the typeResolver must be ObjectType
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        // Check if this field doesn't have a typeResolver
        $subcomponentFieldNodeTypeResolver = $objectTypeResolver->getFieldTypeResolver($field);
        if ($subcomponentFieldNodeTypeResolver === null || !$subcomponentFieldNodeTypeResolver instanceof RelationalTypeResolverInterface) {
            return null;
        }
        return $subcomponentFieldNodeTypeResolver;
    }
}
