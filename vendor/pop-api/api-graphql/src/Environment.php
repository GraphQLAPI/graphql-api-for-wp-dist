<?php

declare (strict_types=1);
namespace PoPAPI\GraphQLAPI;

class Environment
{
    public const PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT = 'PRINT_DYNAMIC_FIELD_IN_EXTENSIONS_OUTPUT';
    public static function disableGraphQLAPI() : bool
    {
        return \getenv('DISABLE_GRAPHQL_API') !== \false ? \strtolower(\getenv('DISABLE_GRAPHQL_API')) === "true" : \false;
    }
}
