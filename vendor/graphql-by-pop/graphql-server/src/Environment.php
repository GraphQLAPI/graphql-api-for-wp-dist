<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer;

class Environment
{
    public const SORT_GRAPHQL_SCHEMA_ALPHABETICALLY = 'SORT_GRAPHQL_SCHEMA_ALPHABETICALLY';
    public const SORT_GLOBAL_FIELDS_AFTER_NORMAL_FIELDS_IN_GRAPHQL_SCHEMA = 'SORT_GLOBAL_FIELDS_AFTER_NORMAL_FIELDS_IN_GRAPHQL_SCHEMA';
    public const ENABLE_PROACTIVE_FEEDBACK = 'ENABLE_PROACTIVE_FEEDBACK';
    public const ENABLE_NESTED_MUTATIONS = 'ENABLE_NESTED_MUTATIONS';
    public const ENABLE_GRAPHQL_INTROSPECTION = 'ENABLE_GRAPHQL_INTROSPECTION';
    public const ADD_VERSION_TO_GRAPHQL_SCHEMA_FIELD_DESCRIPTION = 'ADD_VERSION_TO_GRAPHQL_SCHEMA_FIELD_DESCRIPTION';
    public const ADD_GRAPHQL_INTROSPECTION_PERSISTED_QUERY = 'ADD_GRAPHQL_INTROSPECTION_PERSISTED_QUERY';
    public const ADD_CONNECTION_FROM_ROOT_TO_QUERYROOT_AND_MUTATIONROOT = 'ADD_CONNECTION_FROM_ROOT_TO_QUERYROOT_AND_MUTATIONROOT';
    public const EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA = 'EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA';
    public const EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA = 'EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA';
    public static function enableSettingMutationSchemeByURLParam() : bool
    {
        return \getenv('ENABLE_SETTING_MUTATION_SCHEME_BY_URL_PARAM') !== \false ? \strtolower(\getenv('ENABLE_SETTING_MUTATION_SCHEME_BY_URL_PARAM')) === "true" : \false;
    }
    public static function enableEnablingGraphQLIntrospectionByURLParam() : bool
    {
        return \getenv('ENABLE_ENABLING_GRAPHQL_INTROSPECTION_BY_URL_PARAM') !== \false ? \strtolower(\getenv('ENABLE_ENABLING_GRAPHQL_INTROSPECTION_BY_URL_PARAM')) === "true" : \false;
    }
}
