<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPostMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostsFilterInputObjectTypeResolver;
use PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType\CustomPostMetaQueryInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    /**
     * @var \PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType\CustomPostMetaQueryInputObjectTypeResolver|null
     */
    private $customPostMetaQueryInputObjectTypeResolver;

    /**
     * @param \PoPWPSchema\CustomPostMeta\TypeResolvers\InputObjectType\CustomPostMetaQueryInputObjectTypeResolver $customPostMetaQueryInputObjectTypeResolver
     */
    final public function setCustomPostMetaQueryInputObjectTypeResolver($customPostMetaQueryInputObjectTypeResolver): void
    {
        $this->customPostMetaQueryInputObjectTypeResolver = $customPostMetaQueryInputObjectTypeResolver;
    }
    final protected function getCustomPostMetaQueryInputObjectTypeResolver(): CustomPostMetaQueryInputObjectTypeResolver
    {
        /** @var CustomPostMetaQueryInputObjectTypeResolver */
        return $this->customPostMetaQueryInputObjectTypeResolver = $this->customPostMetaQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(CustomPostMetaQueryInputObjectTypeResolver::class);
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getCustomPostMetaQueryInputObjectTypeResolver();
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCustomPostsFilterInputObjectTypeResolver;
    }
}
