<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers;

class HookHelpers
{
    public const HOOK_ENABLED_FIELD_NAMES = __CLASS__ . ':enabled_field_names';
    public const HOOK_ENABLED_DIRECTIVE_NAMES = __CLASS__ . ':resolved_directives_names';
    /**
     * @param string|null $directiveName
     */
    public static function getHookNameToFilterDirective($directiveName = null) : string
    {
        return self::HOOK_ENABLED_DIRECTIVE_NAMES . ($directiveName ? ':' . $directiveName : '');
    }
    /**
     * @param string|null $fieldName
     */
    public static function getHookNameToFilterField($fieldName = null) : string
    {
        return self::HOOK_ENABLED_FIELD_NAMES . ($fieldName ? ':' . $fieldName : '');
    }
}
