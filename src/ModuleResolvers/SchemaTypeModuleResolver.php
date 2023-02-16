<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Constants\ConfigurationDefaultValues;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface;
use PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver;
use PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver;
use PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver;
use PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver;
use PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver;
use PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver;
use PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver;
use PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SchemaTypeModuleResolver extends AbstractModuleResolver
{
    use ModuleResolverTrait {
        ModuleResolverTrait::hasDocumentation as upstreamHasDocumentation;
    }
    use SchemaTypeModuleResolverTrait;

    public const SCHEMA_CUSTOMPOSTS = Plugin::NAMESPACE . '\schema-customposts';
    public const SCHEMA_POSTS = Plugin::NAMESPACE . '\schema-posts';
    public const SCHEMA_COMMENTS = Plugin::NAMESPACE . '\schema-comments';
    public const SCHEMA_USERS = Plugin::NAMESPACE . '\schema-users';
    public const SCHEMA_USER_ROLES = Plugin::NAMESPACE . '\schema-user-roles';
    public const SCHEMA_USER_AVATARS = Plugin::NAMESPACE . '\schema-user-avatars';
    public const SCHEMA_PAGES = Plugin::NAMESPACE . '\schema-pages';
    public const SCHEMA_MEDIA = Plugin::NAMESPACE . '\schema-media';
    public const SCHEMA_TAGS = Plugin::NAMESPACE . '\schema-tags';
    public const SCHEMA_POST_TAGS = Plugin::NAMESPACE . '\schema-post-tags';
    public const SCHEMA_CATEGORIES = Plugin::NAMESPACE . '\schema-categories';
    public const SCHEMA_POST_CATEGORIES = Plugin::NAMESPACE . '\schema-post-categories';
    public const SCHEMA_MENUS = Plugin::NAMESPACE . '\schema-menus';
    public const SCHEMA_SETTINGS = Plugin::NAMESPACE . '\schema-settings';

    /**
     * Setting options
     */
    public const OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE = 'use-single-type-instead-of-union-type';
    public const OPTION_DEFAULT_AVATAR_SIZE = 'default-avatar-size';
    public const OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT = 'root-comment-list-default-limit';
    public const OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT = 'custompost-comment-list-default-limit';
    public const OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA = 'treat-custompost-status-as-admin-data';
    public const OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA = 'treat-comment-status-as-admin-data';
    public const OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA = 'treat-user-email-as-admin-data';
    public const OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA = 'treat-user-role-as-admin-data';
    public const OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA = 'treat-user-capability-as-admin-data';

    /**
     * This comment used to be valid when using `autowire` functions
     * to automatically inject all services. Since migrating to lazy getters,
     * this same behavior is implicitly covered.
     *
     * Make all properties nullable, becase the ModuleRegistry is registered
     * in the SystemContainer, where there are no typeResolvers so it will be null,
     * and in the ApplicationContainer, from where the "Modules" page is resolved
     * and which does have all the typeResolvers.
     * Function `getDescription` will only be accessed from the Application Container,
     * so the properties will not be null in that situation.
     * @var \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver|null
     */
    private $commentObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver|null
     */
    private $customPostUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver|null
     */
    private $tagUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver|null
     */
    private $categoryUnionTypeResolver;
    /**
     * @var \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver|null
     */
    private $mediaObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver|null
     */
    private $pageObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver|null
     */
    private $genericCustomPostObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver|null
     */
    private $genericTagObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver|null
     */
    private $genericCategoryObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver|null
     */
    private $postTagObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver|null
     */
    private $postCategoryObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver|null
     */
    private $menuObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver|null
     */
    private $postObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver|null
     */
    private $userRoleObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver|null
     */
    private $userAvatarObjectTypeResolver;
    /**
     * @var \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver|null
     */
    private $userObjectTypeResolver;
    /**
     * @var \GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface|null
     */
    private $wpDataModelProvider;
    /**
     * @var \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface|null
     */
    private $markdownContentParser;

    /**
     * @param \PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver $commentObjectTypeResolver
     */
    final public function setCommentObjectTypeResolver($commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        /** @var CommentObjectTypeResolver */
        return $this->commentObjectTypeResolver = $this->commentObjectTypeResolver ?? $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver $customPostUnionTypeResolver
     */
    final public function setCustomPostUnionTypeResolver($customPostUnionTypeResolver): void
    {
        $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
    }
    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        /** @var CustomPostUnionTypeResolver */
        return $this->customPostUnionTypeResolver = $this->customPostUnionTypeResolver ?? $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\UnionType\TagUnionTypeResolver $tagUnionTypeResolver
     */
    final public function setTagUnionTypeResolver($tagUnionTypeResolver): void
    {
        $this->tagUnionTypeResolver = $tagUnionTypeResolver;
    }
    final protected function getTagUnionTypeResolver(): TagUnionTypeResolver
    {
        /** @var TagUnionTypeResolver */
        return $this->tagUnionTypeResolver = $this->tagUnionTypeResolver ?? $this->instanceManager->getInstance(TagUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\UnionType\CategoryUnionTypeResolver $categoryUnionTypeResolver
     */
    final public function setCategoryUnionTypeResolver($categoryUnionTypeResolver): void
    {
        $this->categoryUnionTypeResolver = $categoryUnionTypeResolver;
    }
    final protected function getCategoryUnionTypeResolver(): CategoryUnionTypeResolver
    {
        /** @var CategoryUnionTypeResolver */
        return $this->categoryUnionTypeResolver = $this->categoryUnionTypeResolver ?? $this->instanceManager->getInstance(CategoryUnionTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver $mediaObjectTypeResolver
     */
    final public function setMediaObjectTypeResolver($mediaObjectTypeResolver): void
    {
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }
    final protected function getMediaObjectTypeResolver(): MediaObjectTypeResolver
    {
        /** @var MediaObjectTypeResolver */
        return $this->mediaObjectTypeResolver = $this->mediaObjectTypeResolver ?? $this->instanceManager->getInstance(MediaObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver $pageObjectTypeResolver
     */
    final public function setPageObjectTypeResolver($pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        /** @var PageObjectTypeResolver */
        return $this->pageObjectTypeResolver = $this->pageObjectTypeResolver ?? $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\GenericCustomPostObjectTypeResolver $genericCustomPostObjectTypeResolver
     */
    final public function setGenericCustomPostObjectTypeResolver($genericCustomPostObjectTypeResolver): void
    {
        $this->genericCustomPostObjectTypeResolver = $genericCustomPostObjectTypeResolver;
    }
    final protected function getGenericCustomPostObjectTypeResolver(): GenericCustomPostObjectTypeResolver
    {
        /** @var GenericCustomPostObjectTypeResolver */
        return $this->genericCustomPostObjectTypeResolver = $this->genericCustomPostObjectTypeResolver ?? $this->instanceManager->getInstance(GenericCustomPostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Tags\TypeResolvers\ObjectType\GenericTagObjectTypeResolver $genericTagObjectTypeResolver
     */
    final public function setGenericTagObjectTypeResolver($genericTagObjectTypeResolver): void
    {
        $this->genericTagObjectTypeResolver = $genericTagObjectTypeResolver;
    }
    final protected function getGenericTagObjectTypeResolver(): GenericTagObjectTypeResolver
    {
        /** @var GenericTagObjectTypeResolver */
        return $this->genericTagObjectTypeResolver = $this->genericTagObjectTypeResolver ?? $this->instanceManager->getInstance(GenericTagObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Categories\TypeResolvers\ObjectType\GenericCategoryObjectTypeResolver $genericCategoryObjectTypeResolver
     */
    final public function setGenericCategoryObjectTypeResolver($genericCategoryObjectTypeResolver): void
    {
        $this->genericCategoryObjectTypeResolver = $genericCategoryObjectTypeResolver;
    }
    final protected function getGenericCategoryObjectTypeResolver(): GenericCategoryObjectTypeResolver
    {
        /** @var GenericCategoryObjectTypeResolver */
        return $this->genericCategoryObjectTypeResolver = $this->genericCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(GenericCategoryObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostTags\TypeResolvers\ObjectType\PostTagObjectTypeResolver $postTagObjectTypeResolver
     */
    final public function setPostTagObjectTypeResolver($postTagObjectTypeResolver): void
    {
        $this->postTagObjectTypeResolver = $postTagObjectTypeResolver;
    }
    final protected function getPostTagObjectTypeResolver(): PostTagObjectTypeResolver
    {
        /** @var PostTagObjectTypeResolver */
        return $this->postTagObjectTypeResolver = $this->postTagObjectTypeResolver ?? $this->instanceManager->getInstance(PostTagObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\PostCategories\TypeResolvers\ObjectType\PostCategoryObjectTypeResolver $postCategoryObjectTypeResolver
     */
    final public function setPostCategoryObjectTypeResolver($postCategoryObjectTypeResolver): void
    {
        $this->postCategoryObjectTypeResolver = $postCategoryObjectTypeResolver;
    }
    final protected function getPostCategoryObjectTypeResolver(): PostCategoryObjectTypeResolver
    {
        /** @var PostCategoryObjectTypeResolver */
        return $this->postCategoryObjectTypeResolver = $this->postCategoryObjectTypeResolver ?? $this->instanceManager->getInstance(PostCategoryObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Menus\TypeResolvers\ObjectType\MenuObjectTypeResolver $menuObjectTypeResolver
     */
    final public function setMenuObjectTypeResolver($menuObjectTypeResolver): void
    {
        $this->menuObjectTypeResolver = $menuObjectTypeResolver;
    }
    final protected function getMenuObjectTypeResolver(): MenuObjectTypeResolver
    {
        /** @var MenuObjectTypeResolver */
        return $this->menuObjectTypeResolver = $this->menuObjectTypeResolver ?? $this->instanceManager->getInstance(MenuObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Posts\TypeResolvers\ObjectType\PostObjectTypeResolver $postObjectTypeResolver
     */
    final public function setPostObjectTypeResolver($postObjectTypeResolver): void
    {
        $this->postObjectTypeResolver = $postObjectTypeResolver;
    }
    final protected function getPostObjectTypeResolver(): PostObjectTypeResolver
    {
        /** @var PostObjectTypeResolver */
        return $this->postObjectTypeResolver = $this->postObjectTypeResolver ?? $this->instanceManager->getInstance(PostObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserRolesWP\TypeResolvers\ObjectType\UserRoleObjectTypeResolver $userRoleObjectTypeResolver
     */
    final public function setUserRoleObjectTypeResolver($userRoleObjectTypeResolver): void
    {
        $this->userRoleObjectTypeResolver = $userRoleObjectTypeResolver;
    }
    final protected function getUserRoleObjectTypeResolver(): UserRoleObjectTypeResolver
    {
        /** @var UserRoleObjectTypeResolver */
        return $this->userRoleObjectTypeResolver = $this->userRoleObjectTypeResolver ?? $this->instanceManager->getInstance(UserRoleObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\UserAvatars\TypeResolvers\ObjectType\UserAvatarObjectTypeResolver $userAvatarObjectTypeResolver
     */
    final public function setUserAvatarObjectTypeResolver($userAvatarObjectTypeResolver): void
    {
        $this->userAvatarObjectTypeResolver = $userAvatarObjectTypeResolver;
    }
    final protected function getUserAvatarObjectTypeResolver(): UserAvatarObjectTypeResolver
    {
        /** @var UserAvatarObjectTypeResolver */
        return $this->userAvatarObjectTypeResolver = $this->userAvatarObjectTypeResolver ?? $this->instanceManager->getInstance(UserAvatarObjectTypeResolver::class);
    }
    /**
     * @param \PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver $userObjectTypeResolver
     */
    final public function setUserObjectTypeResolver($userObjectTypeResolver): void
    {
        $this->userObjectTypeResolver = $userObjectTypeResolver;
    }
    final protected function getUserObjectTypeResolver(): UserObjectTypeResolver
    {
        /** @var UserObjectTypeResolver */
        return $this->userObjectTypeResolver = $this->userObjectTypeResolver ?? $this->instanceManager->getInstance(UserObjectTypeResolver::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\WPDataModel\WPDataModelProviderInterface $wpDataModelProvider
     */
    final public function setWPDataModelProvider($wpDataModelProvider): void
    {
        $this->wpDataModelProvider = $wpDataModelProvider;
    }
    final protected function getWPDataModelProvider(): WPDataModelProviderInterface
    {
        /** @var WPDataModelProviderInterface */
        return $this->wpDataModelProvider = $this->wpDataModelProvider ?? $this->instanceManager->getInstance(WPDataModelProviderInterface::class);
    }
    /**
     * @param \GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface $markdownContentParser
     */
    final public function setMarkdownContentParser($markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser = $this->markdownContentParser ?? $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            self::SCHEMA_PAGES,
            self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_USER_AVATARS,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            self::SCHEMA_CATEGORIES,
            self::SCHEMA_POST_CATEGORIES,
            self::SCHEMA_MEDIA,
            self::SCHEMA_MENUS,
            self::SCHEMA_SETTINGS,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     * @param string $module
     */
    public function getDependedModuleLists($module): array
    {
        switch ($module) {
            case self::SCHEMA_USER_ROLES:
            case self::SCHEMA_USER_AVATARS:
                return [
                    [
                        self::SCHEMA_USERS,
                    ],
                ];
            case self::SCHEMA_POSTS:
            case self::SCHEMA_PAGES:
            case self::SCHEMA_COMMENTS:
            case self::SCHEMA_TAGS:
            case self::SCHEMA_CATEGORIES:
                return [
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                    ],
                ];
            case self::SCHEMA_POST_TAGS:
                return [
                    [
                        self::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_TAGS,
                    ],
                ];
            case self::SCHEMA_POST_CATEGORIES:
                return [
                    [
                        self::SCHEMA_POSTS,
                    ],
                    [
                        self::SCHEMA_CATEGORIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    /**
     * @param string $module
     */
    public function getName($module): string
    {
        switch ($module) {
            case self::SCHEMA_POSTS:
                return \__('Posts', 'graphql-api');
            case self::SCHEMA_COMMENTS:
                return \__('Comments', 'graphql-api');
            case self::SCHEMA_USERS:
                return \__('Users', 'graphql-api');
            case self::SCHEMA_USER_ROLES:
                return \__('User Roles', 'graphql-api');
            case self::SCHEMA_USER_AVATARS:
                return \__('User Avatars', 'graphql-api');
            case self::SCHEMA_PAGES:
                return \__('Pages', 'graphql-api');
            case self::SCHEMA_MEDIA:
                return \__('Media', 'graphql-api');
            case self::SCHEMA_TAGS:
                return \__('Tags', 'graphql-api');
            case self::SCHEMA_POST_TAGS:
                return \__('Post Tags', 'graphql-api');
            case self::SCHEMA_CATEGORIES:
                return \__('Categories', 'graphql-api');
            case self::SCHEMA_POST_CATEGORIES:
                return \__('Post Categories', 'graphql-api');
            case self::SCHEMA_MENUS:
                return \__('Menus', 'graphql-api');
            case self::SCHEMA_SETTINGS:
                return \__('Settings', 'graphql-api');
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Custom Posts', 'graphql-api');
            default:
                return $module;
        }
    }

    /**
     * @param string $module
     */
    public function getDescription($module): string
    {
        switch ($module) {
            case self::SCHEMA_POSTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('posts', 'graphql-api'),
                    $this->getPostObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USERS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('users', 'graphql-api'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_ROLES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('user roles', 'graphql-api'),
                    $this->getUserRoleObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_USER_AVATARS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('user avatars', 'graphql-api'),
                    $this->getUserAvatarObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_PAGES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('pages', 'graphql-api'),
                    $this->getPageObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MEDIA:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('media elements', 'graphql-api'),
                    $this->getMediaObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_COMMENTS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('comments', 'graphql-api'),
                    $this->getCommentObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_TAGS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post tags', 'graphql-api'),
                    $this->getPostTagObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_POST_CATEGORIES:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('post categories', 'graphql-api'),
                    $this->getPostCategoryObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_MENUS:
                return sprintf(
                    \__('Query %1$s, through type <code>%2$s</code> added to the schema', 'graphql-api'),
                    \__('menus', 'graphql-api'),
                    $this->getMenuObjectTypeResolver()->getTypeName()
                );
            case self::SCHEMA_SETTINGS:
                return \__('Fetch settings from the site', 'graphql-api');
            case self::SCHEMA_CUSTOMPOSTS:
                return \__('Base functionality for all custom posts', 'graphql-api');
            case self::SCHEMA_TAGS:
                return \__('Base functionality for all tags', 'graphql-api');
            case self::SCHEMA_CATEGORIES:
                return \__('Base functionality for all categories', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Does the module have HTML Documentation?
     * @param string $module
     */
    public function hasDocumentation($module): bool
    {
        switch ($module) {
            case self::SCHEMA_POSTS:
            case self::SCHEMA_PAGES:
            case self::SCHEMA_USERS:
            case self::SCHEMA_USER_ROLES:
            case self::SCHEMA_USER_AVATARS:
            case self::SCHEMA_COMMENTS:
            case self::SCHEMA_POST_TAGS:
            case self::SCHEMA_POST_CATEGORIES:
            case self::SCHEMA_MENUS:
            case self::SCHEMA_MEDIA:
                return false;
        }
        return $this->upstreamHasDocumentation($module);
    }

    /**
     * Indicate if the given value is valid for that option
     * @param mixed $value
     * @param string $module
     * @param string $option
     */
    public function isValidValue($module, $option, $value): bool
    {
        if (
            (
                in_array(
                    $module,
                    [
                        self::SCHEMA_CUSTOMPOSTS,
                        self::SCHEMA_POSTS,
                        self::SCHEMA_USERS,
                        self::SCHEMA_MEDIA,
                        self::SCHEMA_MENUS,
                        self::SCHEMA_TAGS,
                        self::SCHEMA_CATEGORIES,
                        self::SCHEMA_PAGES,
                    ]
                ) && in_array(
                    $option,
                    [
                        ModuleSettingOptions::LIST_DEFAULT_LIMIT,
                        ModuleSettingOptions::LIST_MAX_LIMIT,
                    ]
                )
            ) || (
                $module === self::SCHEMA_COMMENTS
                && in_array(
                    $option,
                    [
                        self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT,
                        self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT,
                        ModuleSettingOptions::LIST_MAX_LIMIT,
                    ]
                )
            )
        ) {
            // It can't be less than -1, or 0
            if ($value < -1 or $value === 0) {
                return false;
            }
        }
        return parent::isValidValue($module, $option, $value);
    }

    /**
     * Default value for an option set by the module
     * @return mixed
     * @param string $module
     * @param string $option
     */
    public function getSettingsDefaultValue($module, $option)
    {
        // Lower the security constraints for the static app
        $useUnsafe = PluginEnvironment::areUnsafeDefaultsEnabled();
        $defaultValues = [
            self::SCHEMA_CUSTOMPOSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE => false,
                self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA => true,
                ModuleSettingOptions::CUSTOMPOST_TYPES => ConfigurationDefaultValues::DEFAULT_CUSTOMPOST_TYPES,
            ],
            self::SCHEMA_POSTS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_PAGES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_USERS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
                self::OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_USER_ROLES => [
                self::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA => true,
                self::OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA => true,
            ],
            self::SCHEMA_MEDIA => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_MENUS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 10,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 100,
            ],
            self::SCHEMA_TAGS => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 20,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 200,
                ModuleSettingOptions::TAG_TAXONOMIES => ConfigurationDefaultValues::DEFAULT_TAG_TAXONOMIES,
            ],
            self::SCHEMA_CATEGORIES => [
                ModuleSettingOptions::LIST_DEFAULT_LIMIT => 20,
                ModuleSettingOptions::LIST_MAX_LIMIT => $useUnsafe ? -1 : 200,
                ModuleSettingOptions::CATEGORY_TAXONOMIES => ConfigurationDefaultValues::DEFAULT_CATEGORY_TAXONOMIES,
            ],
            self::SCHEMA_SETTINGS => [
                ModuleSettingOptions::ENTRIES => $useUnsafe ? [] : [
                    'siteurl',
                    'home',
                    'blogname',
                    'blogdescription',
                    'WPLANG',
                    'posts_per_page',
                    'comments_per_page',
                    'date_format',
                    'time_format',
                    'blog_charset',
                ],
                ModuleSettingOptions::BEHAVIOR => $useUnsafe
                    ? Behaviors::DENY
                    : Behaviors::ALLOW,
            ],
            self::SCHEMA_USER_AVATARS => [
                self::OPTION_DEFAULT_AVATAR_SIZE => 96,
            ],
            self::SCHEMA_COMMENTS => [
                self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT => 10,
                self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT => -1,
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
                self::OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     * @param string $module
     */
    public function getSettings($module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Common variables to set the limit on the schema types
        $limitArg = 'limit';
        $unlimitedValue = -1;
        $defaultLimitMessagePlaceholder = \__('Number of results from querying %s when argument <code>%s</code> is not provided. Use <code>%s</code> for unlimited', 'graphql-api');
        $maxLimitMessagePlaceholder = \__('Maximum number of results from querying %s. Use <code>%s</code> for unlimited', 'graphql-api');
        $sensitiveDataTitlePlaceholder = \__('Treat %s as “sensitive” data', 'graphql-api');
        $sensitiveDataDescPlaceholder = \__('If checked, the <strong>%s</strong> data is exposed in the schema (whether as an object field for querying, or as an input field for filtering) only if the Schema Configuration has option <code>Expose Sensitive Data in the Schema</code> enabled', 'graphql-api');
        $taxonomyDescPlaceholder = \__('This list contains all the "%1$shierarchical" taxonomies which are associated to queryable custom posts, i.e. those selected in "Included custom post types" in the Settings for "Custom Posts". Each %2$s taxonomy\'s associated custom post types is shown under <code>(CPT: ...)</code>. If your desired %2$s taxonomy does not appear here, make sure that all of its associated custom post types are in that allowlist.', 'graphql-api');
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                self::SCHEMA_POSTS,
                self::SCHEMA_USERS,
                self::SCHEMA_MEDIA,
                self::SCHEMA_MENUS,
                self::SCHEMA_TAGS,
                self::SCHEMA_CATEGORIES,
                self::SCHEMA_PAGES,
            ])
        ) {
            $moduleEntities = [
                self::SCHEMA_CUSTOMPOSTS => \__('custom posts', 'graphql-api'),
                self::SCHEMA_POSTS => \__('posts', 'graphql-api'),
                self::SCHEMA_USERS => \__('users', 'graphql-api'),
                self::SCHEMA_MEDIA => \__('media items', 'graphql-api'),
                self::SCHEMA_MENUS => \__('menus', 'graphql-api'),
                self::SCHEMA_TAGS => \__('tags', 'graphql-api'),
                self::SCHEMA_CATEGORIES => \__('categories', 'graphql-api'),
                self::SCHEMA_PAGES => \__('pages', 'graphql-api'),
            ];
            $entities = $moduleEntities[$module];
            $defaultLimitOption = ModuleSettingOptions::LIST_DEFAULT_LIMIT;
            $maxLimitOption = ModuleSettingOptions::LIST_MAX_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $defaultLimitOption,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $defaultLimitOption
                ),
                Properties::TITLE => sprintf(
                    \__('Default limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    $entities,
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];
            $moduleSettings[] = [
                Properties::INPUT => $maxLimitOption,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $maxLimitOption
                ),
                Properties::TITLE => sprintf(
                    \__('Max limit for %s', 'graphql-api'),
                    $entities
                ),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    $entities,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            if ($module === self::SCHEMA_CUSTOMPOSTS) {
                $possibleCustomPostTypes = $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginCustomPostTypes();
                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleCustomPostTypes as $value) {
                    $possibleValues[$value] = $value;
                }
                // Set the setting
                $option = ModuleSettingOptions::CUSTOMPOST_TYPES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included custom post types', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(\__('Select the custom post types that can be queried, to be accessible via <code>%s</code>. A custom post type will be represented by its own type in the schema if available (such as <code>%s</code> or <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api'), $this->getCustomPostUnionTypeResolver()->getTypeName(), $this->getPostObjectTypeResolver()->getTypeName(), $this->getPageObjectTypeResolver()->getTypeName(), $this->getGenericCustomPostObjectTypeResolver()->getTypeName()),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
                ];

                $option = self::OPTION_USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Use single type instead of union type?', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(\__('If type <code>%s</code> is composed of only one type (eg: <code>%s</code>), then directly return this single type, instead of the union type?', 'graphql-api'), $this->getCustomPostUnionTypeResolver()->getTypeName(), $this->getPostObjectTypeResolver()->getTypeName()),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];

                $option = self::OPTION_TREAT_CUSTOMPOST_STATUS_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf($sensitiveDataTitlePlaceholder, \__('custom post status', 'graphql-api')),
                    Properties::DESCRIPTION => sprintf($sensitiveDataDescPlaceholder, \__('custom post status', 'graphql-api')),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            } elseif ($module === self::SCHEMA_USERS) {
                $option = self::OPTION_TREAT_USER_EMAIL_AS_SENSITIVE_DATA;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => sprintf($sensitiveDataTitlePlaceholder, \__('user email', 'graphql-api')),
                    Properties::DESCRIPTION => sprintf($sensitiveDataDescPlaceholder, \__('user email', 'graphql-api')),
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            } elseif ($module === self::SCHEMA_TAGS) {
                $possibleTagTaxonomies = $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginTagTaxonomies();
                $queryableTagTaxonomyNameObjects = $this->getWPDataModelProvider()->getQueryableCustomPostsAssociatedTaxonomies(false);

                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleTagTaxonomies as $tagTaxonomyName) {
                    $tagTaxonomyObject = $queryableTagTaxonomyNameObjects[$tagTaxonomyName];
                    $possibleValues[$tagTaxonomyName] = sprintf(
                        $this->__('%s (CPT: "%s")', 'graphql-api'),
                        $tagTaxonomyName,
                        implode(
                            $this->__('", "', 'graphql-api'),
                            $tagTaxonomyObject->object_type
                        )
                    );
                }
                // Set the setting
                $option = ModuleSettingOptions::TAG_TAXONOMIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included tag taxonomies', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(sprintf('%s<br/><br/>%s', sprintf($taxonomyDescPlaceholder, \__('non-', 'graphql-api'), \__('tag', 'graphql-api')), \__('Select the tag taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api')), $this->getTagUnionTypeResolver()->getTypeName(), $this->getPostTagObjectTypeResolver()->getTypeName(), $this->getGenericTagObjectTypeResolver()->getTypeName()),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
                ];
            } elseif ($module === self::SCHEMA_CATEGORIES) {
                $possibleCategoryTaxonomies = $this->getWPDataModelProvider()->getFilteredNonGraphQLAPIPluginCategoryTaxonomies();
                $queryableCategoryTaxonomyNameObjects = $this->getWPDataModelProvider()->getQueryableCustomPostsAssociatedTaxonomies(true);

                // The possible values must have key and value
                $possibleValues = [];
                foreach ($possibleCategoryTaxonomies as $categoryTaxonomyName) {
                    $categoryTaxonomyObject = $queryableCategoryTaxonomyNameObjects[$categoryTaxonomyName];
                    $possibleValues[$categoryTaxonomyName] = sprintf(
                        $this->__('%s (CPT: "%s")', 'graphql-api'),
                        $categoryTaxonomyName,
                        implode(
                            $this->__('", "', 'graphql-api'),
                            $categoryTaxonomyObject->object_type
                        )
                    );
                }
                // Set the setting
                $option = ModuleSettingOptions::CATEGORY_TAXONOMIES;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Included category taxonomies', 'graphql-api'),
                    Properties::DESCRIPTION => sprintf(sprintf('%s<br/><br/>%s', sprintf($taxonomyDescPlaceholder, '', \__('category', 'graphql-api')), \__('Select the category taxonomies that can be queried, to be accessible via <code>%s</code>. A tag taxonomy will be represented by its own type in the schema if available (such as <code>%s</code>) or, otherwise, via <code>%s</code>.<br/>Press <code>ctrl</code> or <code>shift</code> keys to select more than one', 'graphql-api')), $this->getCategoryUnionTypeResolver()->getTypeName(), $this->getPostCategoryObjectTypeResolver()->getTypeName(), $this->getGenericCategoryObjectTypeResolver()->getTypeName()),
                    Properties::TYPE => Properties::TYPE_ARRAY,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                    Properties::IS_MULTIPLE => true,
                ];
            }
        } elseif ($module === self::SCHEMA_COMMENTS) {
            $option = self::OPTION_ROOT_COMMENT_LIST_DEFAULT_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default limit for querying comments in the Root', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    '<code>Root.comments</code>',
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = self::OPTION_CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default limit for querying comments under a custom post or comment', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $defaultLimitMessagePlaceholder,
                    sprintf(
                        \__('%s and %s', 'graphql-api'),
                        '<code>Commentable.comments</code>',
                        '<code>Comment.responses</code>'
                    ),
                    $limitArg,
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = ModuleSettingOptions::LIST_MAX_LIMIT;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Max limit for querying comments', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    $maxLimitMessagePlaceholder,
                    \__('comments', 'graphql-api'),
                    $unlimitedValue
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => -1,
            ];

            $option = self::OPTION_TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf($sensitiveDataTitlePlaceholder, \__('comment status', 'graphql-api')),
                Properties::DESCRIPTION => sprintf($sensitiveDataDescPlaceholder, \__('comment status', 'graphql-api')),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif (
            in_array($module, [
                self::SCHEMA_SETTINGS,
            ])
        ) {
            $entriesTitle = \__('Settings entries', 'graphql-api');
            $headsUpDesc = \__('<strong>Heads up:</strong> Entries surrounded with <code>/</code> or <code>#</code> are evaluated as regex (regular expressions).', 'graphql-api');
            $entryDesc = \__('<strong>Example:</strong> Any of these entries match option name <code>"%1$s"</code>: %2$s', 'graphql-api');
            $ulStyle = 'list-style: initial; padding-left: 15px;';
            $ulPlaceholder = '<ul style=" ' . $ulStyle . '"><li><code>%s</code></li></ul>';
            $moduleDescriptions = [
                self::SCHEMA_SETTINGS => sprintf(
                    \__('%1$s<hr/>%2$s<hr/>%3$s', 'graphql-api'),
                    sprintf(
                        \__('List of all the option names, to either allow or deny access to, when querying fields <code>%s</code>, <code>%s</code> and <code>%s</code> (one entry per line).', 'graphql-api'),
                        'optionValue',
                        'optionValues',
                        'optionObjectValue'
                    ),
                    $headsUpDesc,
                    sprintf(
                        $entryDesc,
                        'siteurl',
                        sprintf(
                            $ulPlaceholder,
                            implode(
                                '</code></li><li><code>',
                                [
                                    'siteurl',
                                    '/site.*/',
                                    '#site([a-zA-Z]*)#',
                                ]
                            )
                        )
                    )
                ),
            ];
            $option = ModuleSettingOptions::ENTRIES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => $entriesTitle,
                Properties::DESCRIPTION => $moduleDescriptions[$module],
                Properties::TYPE => Properties::TYPE_ARRAY,
            ];

            $option = ModuleSettingOptions::BEHAVIOR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Behavior', 'graphql-api'),
                Properties::DESCRIPTION => \__('Are the entries being allowed or denied access to?<ul><li>👉🏽 Allow access: only the configured entries can be accessed, and no other can.</li><li>👉🏽 Deny access: the configured entries cannot be accessed, all other entries can.</li></ul>', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    Behaviors::ALLOW => \__('Allow access', 'graphql-api'),
                    Behaviors::DENY => \__('Deny access', 'graphql-api'),
                ],
            ];
        } elseif ($module === self::SCHEMA_USER_AVATARS) {
            $option = self::OPTION_DEFAULT_AVATAR_SIZE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default avatar size', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Size of the avatar (in pixels) when not providing argument <code>"size"</code> in field <code>%s.avatar</code>', 'graphql-api'),
                    $this->getUserObjectTypeResolver()->getTypeName()
                ),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => 1,
            ];
        } elseif ($module === self::SCHEMA_USER_ROLES) {
            $option = self::OPTION_TREAT_USER_ROLE_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf($sensitiveDataTitlePlaceholder, \__('user roles', 'graphql-api')),
                Properties::DESCRIPTION => sprintf($sensitiveDataDescPlaceholder, \__('user roles', 'graphql-api')),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $option = self::OPTION_TREAT_USER_CAPABILITY_AS_SENSITIVE_DATA;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf($sensitiveDataTitlePlaceholder, \__('user capabilities', 'graphql-api')),
                Properties::DESCRIPTION => sprintf($sensitiveDataDescPlaceholder, \__('user capabilities', 'graphql-api')),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }

        return $moduleSettings;
    }
}
