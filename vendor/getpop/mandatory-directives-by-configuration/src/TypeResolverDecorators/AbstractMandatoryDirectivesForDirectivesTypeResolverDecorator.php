<?php

declare (strict_types=1);
namespace PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators;

use PoP\ComponentModel\TypeResolvers\AbstractTypeResolver;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
use PoP\MandatoryDirectivesByConfiguration\TypeResolverDecorators\ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
abstract class AbstractMandatoryDirectivesForDirectivesTypeResolverDecorator extends \PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator
{
    use ConfigurableMandatoryDirectivesForDirectivesTypeResolverDecoratorTrait;
    /**
     * By default, it is valid everywhere
     *
     * @return array
     */
    public static function getClassesToAttachTo() : array
    {
        return [\PoP\ComponentModel\TypeResolvers\AbstractTypeResolver::class];
    }
}
