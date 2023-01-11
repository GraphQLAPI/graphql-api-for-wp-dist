<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\Overrides\RelationalTypeDataLoaders\UnionType;

use PoPCMSSchema\CustomPostsWP\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\CustomPosts\ModuleConfiguration as CustomPostsModuleConfiguration;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader;
use PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\UnionType\CustomPostUnionTypeDataLoader as UpstreamCustomPostUnionTypeDataLoader;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\App;
use SplObjectStorage;

/**
 * In the context of WordPress, "Custom Posts" are all posts (eg: posts, pages, attachments, events, etc)
 * Hence, this class can simply inherit from the Post dataloader, and add the post-types for all required types
 */
class CustomPostUnionTypeDataLoader extends UpstreamCustomPostUnionTypeDataLoader
{
    /**
     * @var \PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader|null
     */
    private $customPostTypeDataLoader;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface|null
     */
    private $customPostTypeAPI;

    /**
     * @param \PoPCMSSchema\CustomPosts\RelationalTypeDataLoaders\ObjectType\CustomPostTypeDataLoader $customPostTypeDataLoader
     */
    final public function setCustomPostTypeDataLoader($customPostTypeDataLoader): void
    {
        $this->customPostTypeDataLoader = $customPostTypeDataLoader;
    }
    final protected function getCustomPostTypeDataLoader(): CustomPostTypeDataLoader
    {
        /** @var CustomPostTypeDataLoader */
        return $this->customPostTypeDataLoader = $this->customPostTypeDataLoader ?? $this->instanceManager->getInstance(CustomPostTypeDataLoader::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface $customPostTypeAPI
     */
    final public function setCustomPostTypeAPI($customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI = $this->customPostTypeAPI ?? $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    /**
     * @param array<string|int> $ids
     * @return array<string,mixed>
     */
    public function getQueryToRetrieveObjectsForIDs($ids): array
    {
        $query = $this->getCustomPostTypeDataLoader()->getQueryToRetrieveObjectsForIDs($ids);

        // From all post types from the member typeResolvers
        /** @var CustomPostsModuleConfiguration */
        $moduleConfiguration = App::getModule(CustomPostsModule::class)->getConfiguration();
        $query['custompost-types'] = $moduleConfiguration->getQueryableCustomPostTypes();

        return $query;
    }


    /**
     * @return object[]
     * @param array<string|int> $ids
     */
    protected function getUpstreamObjects($ids): array
    {
        $query = $this->getQueryToRetrieveObjectsForIDs($ids);
        return $this->getCustomPostTypeDataLoader()->executeQuery($query);
    }

    /**
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids): array
    {
        $customPosts = $this->getUpstreamObjects($ids);

        $customPostUnionTypeResolver = $this->getCustomPostUnionTypeResolver();
        $customPostTypeAPI = $this->getCustomPostTypeAPI();

        /**
         * After executing `get_posts` it returns a list of custom posts
         * of class WP_Post, without converting the object to its own post
         * type (eg: EM_Event for an "event" custom post type).
         * Cast the custom posts to their own classes.
         * Group all the customPosts by targetResolverPicker,
         * so that their casting can be executed in a single query per type
         *
         * @var SplObjectStorage<CustomPostObjectTypeResolverPickerInterface,array<string|int,object>>
         */
        $customPostTypeResolverPickerItemCustomPosts = new SplObjectStorage();
        foreach ($customPosts as $customPost) {
            $targetTypeResolverPicker = $customPostUnionTypeResolver->getTargetObjectTypeResolverPicker($customPost);
            if (
                // If `null`, no picker handles this type, then do nothing
                $targetTypeResolverPicker === null
                // Needs be an instance of this interface, or do nothing
                || !($targetTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface)
            ) {
                continue;
            }
            // Add the Custom Post Type as the key, which can uniquely identify the picker
            /** @var CustomPostObjectTypeResolverPickerInterface */
            $targetCustomPostTypeResolverPicker = $targetTypeResolverPicker;
            $customPostID = $customPostTypeAPI->getID($customPost);
            $customPostTypeItemCustomPosts = $customPostTypeResolverPickerItemCustomPosts[$targetCustomPostTypeResolverPicker] ?? [];
            $customPostTypeItemCustomPosts[$customPostID] = $customPost;
            $customPostTypeResolverPickerItemCustomPosts[$targetCustomPostTypeResolverPicker] = $customPostTypeItemCustomPosts;
        }

        /**
         * Cast all objects from the same type in a single query
         *
         * @var SplObjectStorage<CustomPostObjectTypeResolverPickerInterface,array<string|int,object>>
         */
        $castedCustomPosts = new SplObjectStorage();
        /** @var CustomPostObjectTypeResolverPickerInterface $customPostTypeTypeResolverPicker */
        foreach ($customPostTypeResolverPickerItemCustomPosts as $customPostTypeTypeResolverPicker) {
            /** @var array<string|int,object> */
            $customPostIDObjects = $customPostTypeResolverPickerItemCustomPosts[$customPostTypeTypeResolverPicker];
            $castedCustomPosts[$customPostTypeTypeResolverPicker] = $customPostTypeTypeResolverPicker->maybeCastCustomPosts($customPostIDObjects);
        }

        // Replace each custom post with its casted object
        $customPosts = array_map(
            function ($customPost) use ($castedCustomPosts, $customPostUnionTypeResolver, $customPostTypeAPI) {
                $targetTypeResolverPicker = $customPostUnionTypeResolver->getTargetObjectTypeResolverPicker($customPost);
                if (
                    $targetTypeResolverPicker === null
                    || !($targetTypeResolverPicker instanceof CustomPostObjectTypeResolverPickerInterface)
                ) {
                    return $customPost;
                }
                $customPostID = $customPostTypeAPI->getID($customPost);
                return $castedCustomPosts[$targetTypeResolverPicker][$customPostID];
            },
            $customPosts
        );
        return $customPosts;
    }
}
