<?php

declare (strict_types=1);
namespace PoPSchema\Taxonomies\Facades;

use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
use PoP\Root\Container\ContainerBuilderFactory;
class TaxonomyTypeAPIFacade
{
    public static function getInstance() : \PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface
    {
        /**
         * @var TaxonomyTypeAPIInterface
         */
        $service = \PoP\Root\Container\ContainerBuilderFactory::getInstance()->get(\PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface::class);
        return $service;
    }
}
