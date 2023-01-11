<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostsWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\CustomPosts\Constants\CustomPostOrderBy;
use PoPCMSSchema\CustomPosts\Enums\CustomPostStatus;
use PoPCMSSchema\CustomPosts\TypeAPIs\AbstractCustomPostTypeAPI as UpstreamAbstractCustomPostTypeAPI;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_Post;

use function get_post_status;
use function get_posts;
use function esc_sql;
use function get_the_excerpt;
use function get_post_types;
use function get_permalink;
use function get_sample_permalink;
use function strip_shortcodes;
use function get_post;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractCustomPostTypeAPI extends UpstreamAbstractCustomPostTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';
    public const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';
    public const HOOK_STATUS_QUERY_ARG_VALUE = __CLASS__ . ':status-query-arg-value';

    /**
     * Indicates if the passed object is of type (Generic)CustomPost
     * @param object $object
     */
    public function isInstanceOfCustomPostType($object): bool
    {
        return $object instanceof WP_Post;
    }

    /**
     * Indicate if an post with provided ID exists
     * @param int|string $id
     */
    public function customPostExists($id): bool
    {
        return $this->getCustomPost($id) !== null;
    }

    /**
     * Return the post's ID
     * @return string|int
     * @param object $customPost
     */
    public function getID($customPost)
    {
        /** @var WP_Post $customPost */
        return $customPost->ID;
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getStatus($customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        $status = get_post_status($customPostID);
        if ($status === false) {
            return null;
        }
        return $status;
    }

    /**
     * If the "status" is not passed, then it's always "publish"
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryDefaults(): array
    {
        return [
            'status' => [
                CustomPostStatus::PUBLISH,
            ],
        ];
    }

    /**
     * Query args that must always be in the query
     *
     * @return array<string,mixed>
     */
    public function getCustomPostQueryRequiredArgs(): array
    {
        return [];
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPosts($query, $options = []): array
    {
        $query = $this->convertCustomPostsQuery($query, $options);
        return get_posts($query);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getCustomPostCount($query, $options = []): int
    {
        // Convert parameters
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertCustomPostsQuery($query, $options);

        // All results, no offset
        $query['posts_per_page'] = -1;
        unset($query['offset']);

        // Execute query and count results
        $posts = get_posts($query);
        return count($posts);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function convertCustomPostsQuery($query, $options = []): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }
        $item0Unpacked = $this->getCustomPostQueryDefaults();
        $item2Unpacked = $this->getCustomPostQueryRequiredArgs();

        // The query overrides the defaults, and is overriden by the required args
        $query = array_merge($item0Unpacked, $query, $item2Unpacked);

        // Convert the parameters
        if (isset($query['status'])) {
            /**
             * This can be both an array and a single value
             *
             * @var string|string[]
             */
            $status = $query['status'];
            unset($query['status']);
            /**
             * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
             *       Until then, this code is commented
             *
             * The status may need to be converted to some underlying value
             */
            // $query['post_status'] = is_array($status)
            //     ? array_map(
            //         $this->getStatusQueryArgValue(...),
            //         $status
            //     )
            //     : $this->getStatusQueryArgValue($status);
            $query['post_status'] = $status;
        }
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['post__not_in'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        // If querying "customPostCount(postTypes:[])" it would reset the list to only "post"
        // So check that postTypes is not empty
        if (isset($query['custompost-types']) && !empty($query['custompost-types'])) {
            $query['post_type'] = $query['custompost-types'];
            unset($query['custompost-types']);
        } else {
            // If not adding the post types, WordPress only uses "post", so querying by CPT would fail loading data
            $query['post_type'] = $this->getCustomPostTypes([
                'publicly-queryable' => true,
            ]);
        }
        // Querying "attachment" doesn't work in an array!
        if (is_array($query['post_type']) && count($query['post_type']) === 1) {
            $query['post_type'] = $query['post_type'][0];
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            $query['posts_per_page'] = $limit;
            unset($query['limit']);
        }
        if (isset($query['order'])) {
            $query['order'] = esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // Maybe replace the provided value
            $query['orderby'] = esc_sql($this->getOrderByQueryArgValue($query['orderby']));
        }
        // Post slug
        if (isset($query['slug'])) {
            $query['name'] = $query['slug'];
            unset($query['slug']);
        }
        if (isset($query['post-not-in'])) {
            $query['post__not_in'] = $query['post-not-in'];
            unset($query['post-not-in']);
        }
        if (isset($query['search'])) {
            $query['is_search'] = true;
            $query['s'] = $query['search'];
            unset($query['search']);
        }
        // Filtering by date: Instead of operating on the query, it does it through filter 'posts_where'
        if (isset($query['date-from'])) {
            $query['date_query'][] = [
                'after' => $query['date-from'],
                'inclusive' => false,
            ];
            unset($query['date-from']);
        }
        if (isset($query['date-to'])) {
            $query['date_query'][] = [
                'before' => $query['date-to'],
                'inclusive' => false,
            ];
            unset($query['date-to']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
     *       Until then, this code is commented
     *
     * Allow "auto_draft" to be converted to "auto-draft"
     * @param string $orderBy
     */
    // protected function getStatusQueryArgValue(string $status): string
    // {
    //     return App::applyFilters(
    //         self::HOOK_STATUS_QUERY_ARG_VALUE,
    //         $status
    //     );
    // }
    protected function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case CustomPostOrderBy::ID:
                $orderBy = 'ID';
                break;
            case CustomPostOrderBy::TITLE:
                $orderBy = 'title';
                break;
            case CustomPostOrderBy::DATE:
                $orderBy = 'date';
                break;
            default:
                $orderBy = $orderBy;
                break;
        }
        return App::applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
    /**
     * @return string[]
     * @param array<string,mixed> $query
     */
    public function getCustomPostTypes($query = array()): array
    {
        // Convert the parameters
        if (isset($query['exclude-from-search'])) {
            $query['exclude_from_search'] = $query['exclude-from-search'];
            unset($query['exclude-from-search']);
        }
        if (isset($query['publicly-queryable'])) {
            $query['publicly_queryable'] = $query['publicly-queryable'];
            unset($query['publicly-queryable']);
        }
        /** @var string[] */
        return get_post_types($query);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getPermalink($customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        if ($this->getStatus($customPostObjectOrID) === CustomPostStatus::PUBLISH) {
            $permalink = get_permalink($customPostID);
            if ($permalink === false) {
                return null;
            }
            return $permalink;
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink($customPostID, null, null);
        return str_replace(['%pagename%', '%postname%'], $post_name, $permalink);
    }


    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getSlug($customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        if ($this->getStatus($customPostObjectOrID) === CustomPostStatus::PUBLISH) {
            return $customPost->post_name;
        }

        // Function get_sample_permalink comes from the file below, so it must be included
        // Code below copied from `function get_sample_permalink_html`
        include_once ABSPATH . 'wp-admin/includes/post.php';
        list($permalink, $post_name) = get_sample_permalink((int)$customPostID, null, null);
        return $post_name;
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getExcerpt($customPostObjectOrID): ?string
    {
        $customPostID = $this->getCustomPostID($customPostObjectOrID);
        return get_the_excerpt($customPostID);
    }

    /**
     * @return array{0:WP_Post|null,1:null|string|int}
     * @param string|int|object $customPostObjectOrID
     */
    protected function getCustomPostObjectAndID($customPostObjectOrID): array
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            $customPostID = $customPost->ID;
        } else {
            $customPostID = $customPostObjectOrID;
            /** @var WP_Post|null */
            $customPost = \get_post((int)$customPostID);
        }
        return [
            $customPost,
            $customPostID,
        ];
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    protected function getCustomPostObject($customPostObjectOrID)
    {
        if (is_object($customPostObjectOrID)) {
            return $customPostObjectOrID;
        }
        /** @var string|int */
        $customPostID = $customPostObjectOrID;
        return \get_post((int)$customPostID);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    protected function getCustomPostID($customPostObjectOrID): int
    {
        if (is_object($customPostObjectOrID)) {
            /** @var WP_Post */
            $customPost = $customPostObjectOrID;
            return $customPost->ID;
        }
        return (int)$customPostObjectOrID;
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getTitle($customPostObjectOrID): ?string
    {
        list(
            $customPost,
            $customPostID,
        ) = $this->getCustomPostObjectAndID($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        /** @var WP_Post $customPost */
        return App::applyFilters('the_title', $customPost->post_title, $customPostID);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getContent($customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return App::applyFilters('the_content', $customPost->post_content);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getRawContent($customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }

        // Basic content: remove embeds, shortcodes, and tags
        // Remove unneeded filters, then add them again
        // @see wp-includes/default-filters.php
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        $wp_embed = $GLOBALS['wp_embed'];
        App::removeFilter('the_content', \Closure::fromCallable([$wp_embed, 'autoembed']), 8);
        App::removeFilter('the_content', \Closure::fromCallable('wpautop'));

        // Do not allow HTML tags or shortcodes
        $ret = strip_shortcodes($customPost->post_content);
        $ret = App::applyFilters('the_content', $ret);
        App::addFilter('the_content', \Closure::fromCallable([$wp_embed, 'autoembed']), 8);
        App::addFilter('the_content', \Closure::fromCallable('wpautop'));

        return strip_tags($ret);
    }

    /**
     * @param string|int|object $customPostObjectOrID
     * @param bool $gmt
     */
    public function getPublishedDate($customPostObjectOrID, $gmt = false): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_date_gmt : $customPost->post_date;
    }

    /**
     * @param string|int|object $customPostObjectOrID
     * @param bool $gmt
     */
    public function getModifiedDate($customPostObjectOrID, $gmt = false): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        if ($customPost === null) {
            return null;
        }
        return $gmt ? $customPost->post_modified_gmt : $customPost->post_modified;
    }
    /**
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostType($customPostObjectOrID): ?string
    {
        /** @var WP_Post|null */
        $customPost = $this->getCustomPostObject($customPostObjectOrID);
        return ($customPost2 = $customPost) ? $customPost2->post_type : null;
    }

    /**
     * Get the post with provided ID or, if it doesn't exist, null
     * @param int|string $id
     */
    public function getCustomPost($id)
    {
        /** @var object|null */
        return get_post((int)$id);
    }
}
