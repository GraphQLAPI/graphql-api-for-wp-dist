<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeResolvers\EnumType;

class HookNames
{
    public const ENUM_VALUES = __CLASS__ . ':enum-values';
    public const ADMIN_ENUM_VALUES = __CLASS__ . ':admin-enum-values';
    public const ENUM_VALUE_DESCRIPTION = __CLASS__ . ':enum-value-description';
    public const ENUM_VALUE_DEPRECATION_MESSAGE = __CLASS__ . ':enum-value-deprecation-message';
    public const ENUM_VALUE_EXTENSIONS = __CLASS__ . ':enum-value-extensions';
}
