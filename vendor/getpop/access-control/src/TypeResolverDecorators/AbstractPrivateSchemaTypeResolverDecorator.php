<?php

declare (strict_types=1);
namespace PoP\AccessControl\TypeResolverDecorators;

use PoP\AccessControl\ComponentConfiguration;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator;
abstract class AbstractPrivateSchemaTypeResolverDecorator extends \PoP\ComponentModel\TypeResolverDecorators\AbstractTypeResolverDecorator
{
    /**
     * Enable only for private schema
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function enabled(\PoP\ComponentModel\TypeResolvers\TypeResolverInterface $typeResolver) : bool
    {
        return \PoP\AccessControl\ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode() || \PoP\AccessControl\ComponentConfiguration::usePrivateSchemaMode();
    }
    protected function getSchemaMode() : string
    {
        return \PoP\AccessControl\Schema\SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
