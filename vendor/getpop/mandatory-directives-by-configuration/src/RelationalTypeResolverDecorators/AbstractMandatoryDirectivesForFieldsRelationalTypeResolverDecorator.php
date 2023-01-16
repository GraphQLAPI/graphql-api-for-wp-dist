<?php

declare (strict_types=1);
namespace PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators;

use PoP\ComponentModel\RelationalTypeResolverDecorators\AbstractRelationalTypeResolverDecorator;
abstract class AbstractMandatoryDirectivesForFieldsRelationalTypeResolverDecorator extends AbstractRelationalTypeResolverDecorator
{
    use \PoP\MandatoryDirectivesByConfiguration\RelationalTypeResolverDecorators\ConfigurableMandatoryDirectivesForFieldsRelationalTypeResolverDecoratorTrait;
}