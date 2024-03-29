<?php

declare(strict_types=1);

namespace PoPWPSchema\Meta\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\InputObjectType\HookNames;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractRemoveMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::INPUT_FIELD_NAME_TYPE_RESOLVERS,
            \Closure::fromCallable([$this, 'getInputFieldNameTypeResolvers']),
            100,
            2
        );
    }

    /**
     * Indicate if to remove the fields added by the SchemaHookSet
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    abstract protected function removeMetaQueryInputFields($inputObjectTypeResolver): bool;

    /**
     * Remove the fields added by the SchemaHookSet
     *
     * @param array<string,InputTypeResolverInterface> $inputFieldNameTypeResolvers
     * @return array<string,InputTypeResolverInterface>
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    public function getInputFieldNameTypeResolvers($inputFieldNameTypeResolvers, $inputObjectTypeResolver): array
    {
        if (!$this->removeMetaQueryInputFields($inputObjectTypeResolver)) {
            return $inputFieldNameTypeResolvers;
        }
        unset($inputFieldNameTypeResolvers['metaQuery']);
        return $inputFieldNameTypeResolvers;
    }
}
