<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

abstract class AbstractConfigurableAccessControlForDirectivesInPublicSchemaTypeResolverDecorator extends \PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator
{
    use ConfigurableAccessControlForDirectivesTypeResolverDecoratorTrait;
}
