<?php

declare (strict_types=1);
namespace PoPAPI\API;

class Environment
{
    public const USE_SCHEMA_DEFINITION_CACHE = 'USE_SCHEMA_DEFINITION_CACHE';
    public const SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA = 'SKIP_EXPOSING_GLOBAL_FIELDS_IN_FULL_SCHEMA';
    public const SORT_FULL_SCHEMA_ALPHABETICALLY = 'SORT_FULL_SCHEMA_ALPHABETICALLY';
    public const DISABLE_API = 'DISABLE_API';
    public const ENABLE_SETTING_NAMESPACING_BY_URL_PARAM = 'ENABLE_SETTING_NAMESPACING_BY_URL_PARAM';
    public const ADD_FULLSCHEMA_FIELD_TO_SCHEMA = 'ADD_FULLSCHEMA_FIELD_TO_SCHEMA';
    public const ENABLE_PASSING_PERSISTED_QUERY_NAME_VIA_URL_PARAM = 'ENABLE_PASSING_PERSISTED_QUERY_NAME_VIA_URL_PARAM';
    public static function disableAPI() : bool
    {
        return \getenv(self::DISABLE_API) !== \false ? \strtolower(\getenv(self::DISABLE_API)) === "true" : \false;
    }
    public static function enableSettingNamespacingByURLParam() : bool
    {
        return \getenv(self::ENABLE_SETTING_NAMESPACING_BY_URL_PARAM) !== \false ? \strtolower(\getenv(self::ENABLE_SETTING_NAMESPACING_BY_URL_PARAM)) === "true" : \false;
    }
}
