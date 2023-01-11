<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\EnumType\HookNames;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPosts\TypeResolvers\EnumType\FilterCustomPostStatusEnumTypeResolver;
use PoPWPSchema\CustomPosts\Enums\CustomPostStatus;

/**
 * Add the additional WordPress Post Statuses
 *
 * @see https://wordpress.org/support/article/post-status/
 */
class FilterCustomPostStatusEnumTypeHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            HookNames::ENUM_VALUES,
            \Closure::fromCallable([$this, 'getEnumValues']),
            10,
            2
        );
        App::addFilter(
            HookNames::ADMIN_ENUM_VALUES,
            \Closure::fromCallable([$this, 'getSensitiveEnumValues']),
            10,
            2
        );
        App::addFilter(
            HookNames::ENUM_VALUE_DESCRIPTION,
            \Closure::fromCallable([$this, 'getEnumValueDescription']),
            10,
            3
        );
    }

    /**
     * @param string[] $enumValues
     * @return string[]
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    public function getEnumValues($enumValues, $enumTypeResolver): array
    {
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $enumValues;
        }
        return array_merge(
            $enumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                CustomPostStatus::INHERIT,
                /**
                 * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
                 *       Until then, this code is commented
                 */
                // CustomPostStatus::AUTO_DRAFT,
            ]
        );
    }

    /**
     * @param string[] $adminEnumValues
     * @return string[]|mixed[]
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     */
    public function getSensitiveEnumValues($adminEnumValues, $enumTypeResolver): array
    {
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $adminEnumValues;
        }
        return array_merge(
            $adminEnumValues,
            [
                CustomPostStatus::FUTURE,
                CustomPostStatus::PRIVATE,
                CustomPostStatus::INHERIT,
                /**
                 * @todo "auto-draft" must be converted to enum value "auto_draft" on `Post.status`.
                 *       Until then, this code is commented
                 */
                //CustomPostStatus::AUTO_DRAFT,
            ]
        );
    }

    /**
     * @param string|null $enumValueDescription
     * @param \PoP\ComponentModel\TypeResolvers\EnumType\EnumTypeResolverInterface $enumTypeResolver
     * @param string $enumValue
     */
    public function getEnumValueDescription(
        $enumValueDescription,
        $enumTypeResolver,
        $enumValue
    ): ?string {
        if (!($enumTypeResolver instanceof FilterCustomPostStatusEnumTypeResolver)) {
            return $enumValueDescription;
        }
        switch ($enumValue) {
            case CustomPostStatus::FUTURE:
                return $this->__('Future content - custom posts to publish in the future', 'customposts');
            case CustomPostStatus::PRIVATE:
                return $this->__('Private content - not visible to users who are not logged in', 'customposts');
            case CustomPostStatus::INHERIT:
                return $this->__('Used with a child custom post (such as Attachments and Revisions) to determine the actual status from the parent custom post', 'customposts');
            default:
                return $enumValueDescription;
        }
    }
}
