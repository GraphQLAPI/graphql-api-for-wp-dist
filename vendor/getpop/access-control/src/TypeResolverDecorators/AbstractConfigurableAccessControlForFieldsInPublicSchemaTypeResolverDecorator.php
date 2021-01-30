<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

abstract class AbstractConfigurableAccessControlForFieldsInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForFieldsTypeResolverDecoratorTrait;
}
