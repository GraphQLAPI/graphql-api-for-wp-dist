<?php

declare (strict_types=1);
namespace GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType;

class DirectiveExtensionsObjectTypeResolver extends \GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractSchemaElementExtensionsObjectTypeResolver
{
    public function getIntrospectionTypeName() : string
    {
        return 'DirectiveExtensions';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Extensions (custom metadata) added to the directive', 'graphql-server');
    }
}
