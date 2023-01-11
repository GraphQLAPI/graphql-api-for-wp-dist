<?php

declare(strict_types=1);

namespace PoPCMSSchema\TagsWP\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\TagListTypeAPIInterface;
use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface;
use PoPCMSSchema\TaxonomiesWP\TypeAPIs\AbstractTaxonomyTypeAPI;
use PoP\Root\App;
use WP_Error;
use WP_Post;
use WP_Term;

use function get_tags;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
abstract class AbstractTagTypeAPI extends AbstractTaxonomyTypeAPI implements TagTypeAPIInterface, TagListTypeAPIInterface
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    abstract protected function getTagTaxonomyName(): string;

    /**
     * Indicates if the passed object is of type Tag
     * @param object $object
     */
    public function isInstanceOfTagType($object): bool
    {
        if (!$this->isInstanceOfTaxonomyTermType($object)) {
            return false;
        }
        /** @var WP_Term $object */
        return $object->taxonomy === $this->getTagTaxonomyName();
    }

    protected function isHierarchical(): bool
    {
        return false;
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    protected function getTagFromObjectOrID($tagObjectOrID): ?WP_Term
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermFromObjectOrID($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagName($tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermName($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int $tagID
     */
    public function getTag($tagID)
    {
        return $this->getTaxonomyTerm($tagID, $this->getTagTaxonomyName());
    }

    /**
     * @param int|string $id
     */
    public function tagExists($id): bool
    {
        return $this->getTag($id) !== null;
    }

    /**
     * @param string $tagName
     */
    public function getTagByName($tagName)
    {
        return $this->getTaxonomyTermByName($tagName, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|WP_Post $customPostObjectOrID
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @return array<string|int>|object[]
     */
    public function getCustomPostTags($customPostObjectOrID, $query = [], $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyName();
        }

        /** @var array<string|int>|object[] */
        return $this->getCustomPostTaxonomyTerms($customPostObjectOrID, $query, $options);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     * @param string|int|object $customPostObjectOrID
     */
    public function getCustomPostTagCount($customPostObjectOrID, $query = [], $options = []): ?int
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { categories(taxonomy: some_category) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyName();
        }

        /** @var string|int|WP_Post $customPostObjectOrID */
        return $this->getCustomPostTaxonomyTermCount($customPostObjectOrID, $query, $options);
    }
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTagCount($query = [], $options = []): int
    {
        /** @var int */
        return $this->getTaxonomyCount($query, $options);
    }

    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getTags($query, $options = []): array
    {
        $query = $this->convertTagsQuery($query, $options);
        $tags = get_tags($query);
        if ($tags instanceof WP_Error) {
            return [];
        }
        /** @var object[] */
        return $tags;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertTaxonomyTermsQuery($query, $options = []): array
    {
        /**
         * Allow to set the taxonomy in advance via a fieldArg.
         * Eg: { customPosts { tags(taxonomy: nav_menu) { id } }
         */
        if (!isset($query['taxonomy'])) {
            $query['taxonomy'] = $this->getTagTaxonomyName();
        }
        $query = parent::convertTaxonomyTermsQuery($query, $options);
        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    final public function convertTagsQuery($query, $options = []): array
    {
        return $this->convertTaxonomyTermsQuery($query, $options);
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagURL($tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURL($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagURLPath($tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermURLPath($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagSlug($tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermSlug($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagDescription($tagObjectOrID): ?string
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermDescription($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @param string|int|object $tagObjectOrID
     */
    public function getTagItemCount($tagObjectOrID): ?int
    {
        /** @var string|int|WP_Term $tagObjectOrID */
        return $this->getTaxonomyTermItemCount($tagObjectOrID, $this->getTagTaxonomyName());
    }

    /**
     * @return string|int
     * @param object $tag
     */
    public function getTagID($tag)
    {
        /** @var WP_Term $tag */
        return $this->getTaxonomyTermID($tag);
    }
}
