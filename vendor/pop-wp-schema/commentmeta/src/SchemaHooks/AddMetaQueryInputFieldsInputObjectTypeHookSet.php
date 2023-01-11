<?php

declare(strict_types=1);

namespace PoPWPSchema\CommentMeta\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;
use PoPCMSSchema\Comments\TypeResolvers\InputObjectType\AbstractCommentsFilterInputObjectTypeResolver;
use PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType\CommentMetaQueryInputObjectTypeResolver;
use PoPWPSchema\Meta\SchemaHooks\AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet;
use PoPWPSchema\Meta\TypeResolvers\InputObjectType\AbstractMetaQueryInputObjectTypeResolver;

class AddMetaQueryInputFieldsInputObjectTypeHookSet extends AbstractAddMetaQueryInputFieldsInputObjectTypeHookSet
{
    /**
     * @var \PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType\CommentMetaQueryInputObjectTypeResolver|null
     */
    private $commentMetaQueryInputObjectTypeResolver;

    /**
     * @param \PoPWPSchema\CommentMeta\TypeResolvers\InputObjectType\CommentMetaQueryInputObjectTypeResolver $commentMetaQueryInputObjectTypeResolver
     */
    final public function setCommentMetaQueryInputObjectTypeResolver($commentMetaQueryInputObjectTypeResolver): void
    {
        $this->commentMetaQueryInputObjectTypeResolver = $commentMetaQueryInputObjectTypeResolver;
    }
    final protected function getCommentMetaQueryInputObjectTypeResolver(): CommentMetaQueryInputObjectTypeResolver
    {
        /** @var CommentMetaQueryInputObjectTypeResolver */
        return $this->commentMetaQueryInputObjectTypeResolver = $this->commentMetaQueryInputObjectTypeResolver ?? $this->instanceManager->getInstance(CommentMetaQueryInputObjectTypeResolver::class);
    }

    protected function getMetaQueryInputObjectTypeResolver(): AbstractMetaQueryInputObjectTypeResolver
    {
        return $this->getCommentMetaQueryInputObjectTypeResolver();
    }

    /**
     * @param \PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface $inputObjectTypeResolver
     */
    protected function isInputObjectTypeResolver($inputObjectTypeResolver): bool
    {
        return $inputObjectTypeResolver instanceof AbstractCommentsFilterInputObjectTypeResolver;
    }
}
