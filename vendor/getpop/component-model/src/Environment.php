<?php

declare (strict_types=1);
namespace PoP\ComponentModel;

use PoP\Definitions\Configuration\Request as DefinitionsRequest;
class Environment
{
    public const ENABLE_COMPONENT_MODEL_CACHE = 'ENABLE_COMPONENT_MODEL_CACHE';
    public const USE_COMPONENT_MODEL_CACHE = 'USE_COMPONENT_MODEL_CACHE';
    public const NAMESPACE_TYPES_AND_INTERFACES = 'NAMESPACE_TYPES_AND_INTERFACES';
    public const ENABLE_MUTATIONS = 'ENABLE_MUTATIONS';
    public const EXPOSE_SENSITIVE_DATA_IN_SCHEMA = 'EXPOSE_SENSITIVE_DATA_IN_SCHEMA';
    public const VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION = 'VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION';
    public const SET_FIELD_AS_NULL_IF_DIRECTIVE_FAILED = 'SET_FIELD_AS_NULL_IF_DIRECTIVE_FAILED';
    public const CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST = 'CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST';
    public const ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE = 'ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE';
    public const SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA = 'SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA';
    public const ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST = 'ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST';
    public const ENABLE_FEEDBACK_CATEGORY_EXTENSIONS = 'ENABLE_FEEDBACK_CATEGORY_EXTENSIONS';
    public const LOG_EXCEPTION_ERROR_MESSAGES_AND_TRACES = 'LOG_EXCEPTION_ERROR_MESSAGES_AND_TRACES';
    public const SEND_EXCEPTION_ERROR_MESSAGES = 'SEND_EXCEPTION_ERROR_MESSAGES';
    public const SEND_EXCEPTION_TRACES = 'SEND_EXCEPTION_TRACES';
    public const ENABLE_SELF_FIELD = 'ENABLE_SELF_FIELD';
    public const ENABLE_TYPENAME_GLOBAL_FIELDS = 'ENABLE_TYPENAME_GLOBAL_FIELDS';
    public const EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS = 'EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS';
    public const EXPOSE_SCHEMA_TYPE_DIRECTIVE_LOCATIONS = 'EXPOSE_SCHEMA_TYPE_DIRECTIVE_LOCATIONS';
    public const ENABLE_SEMANTIC_VERSION_CONSTRAINTS = 'ENABLE_SEMANTIC_VERSION_CONSTRAINTS';
    public const CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME = 'CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME';
    // public static function failIfSubcomponentTypeResolverUndefined()
    // {
    //     return getenv('FAIL_IF_SUBCOMPONENT_TYPERESOLVER_IS_UNDEFINED') !== false ? strtolower(getenv('FAIL_IF_SUBCOMPONENT_TYPERESOLVER_IS_UNDEFINED')) === "true" : false;
    // }
    public static function enableExtraRoutesByParams() : bool
    {
        return \getenv('ENABLE_EXTRA_ROUTES_BY_PARAMS') !== \false ? \strtolower(\getenv('ENABLE_EXTRA_ROUTES_BY_PARAMS')) === "true" : \false;
    }
    /**
     * Use 'components' or 'm' in the JS context. Used to compress the file size in PROD
     */
    public static function compactResponseJsonKeys() : bool
    {
        // Do not compact if not mangled
        if (!DefinitionsRequest::isMangled()) {
            return \false;
        }
        return \getenv('COMPACT_RESPONSE_JSON_KEYS') !== \false ? \strtolower(\getenv('COMPACT_RESPONSE_JSON_KEYS')) === "true" : \false;
    }
}
