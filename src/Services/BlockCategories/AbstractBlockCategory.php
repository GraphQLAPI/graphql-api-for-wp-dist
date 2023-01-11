<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\BlockCategories;

use PoP\Root\Services\BasicServiceTrait;
use PoP\Root\Services\AbstractAutomaticallyInstantiatedService;
use WP_Block_Editor_Context;
use WP_Post;

abstract class AbstractBlockCategory extends AbstractAutomaticallyInstantiatedService implements BlockCategoryInterface
{
    use BasicServiceTrait;

    final public function initialize(): void
    {
        /**
         * Starting from WP 5.8 the hook is a different one
         *
         * @see https://github.com/leoloso/PoP/issues/711
         */
        if (\is_wp_version_compatible('5.8')) {
            \add_filter(
                'block_categories_all',
                \Closure::fromCallable([$this, 'getBlockCategoriesViaBlockEditorContext']),
                10,
                2
            );
        } else {
            \add_filter(
                'block_categories',
                \Closure::fromCallable([$this, 'getBlockCategories']),
                10,
                2
            );
        }
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [];
    }

    /**
     * Block category's slug
     */
    abstract protected function getBlockCategorySlug(): string;

    /**
     * Block category's title
     */
    abstract protected function getBlockCategoryTitle(): string;

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array<string,mixed>> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array<string,mixed>> List of categories, each item is an array with props "slug" and "title"
     * @param \WP_Block_Editor_Context|null $blockEditorContext
     */
    public function getBlockCategoriesViaBlockEditorContext($categories, $blockEditorContext): array
    {
        if ($blockEditorContext === null || $blockEditorContext->post === null) {
            return $categories;
        }
        return $this->getBlockCategories(
            $categories,
            $blockEditorContext->post
        );
    }

    /**
     * Register the category when in the corresponding CPT
     *
     * @param array<array<string,mixed>> $categories List of categories, each item is an array with props "slug" and "title"
     * @return array<array<string,mixed>> List of categories, each item is an array with props "slug" and "title"
     * @param \WP_Post $post
     */
    public function getBlockCategories($categories, $post): array
    {
        /**
         * If specified CPTs, register the category only for them
         */
        if (empty($this->getCustomPostTypes()) || in_array($post->post_type, $this->getCustomPostTypes())) {
            return array_merge($categories, [[
                'slug' => $this->getBlockCategorySlug(),
                'title' => $this->getBlockCategoryTitle(),
            ]]);
        }

        return $categories;
    }
}
