<?php

declare (strict_types=1);
namespace PoP\CacheControl\DirectiveResolvers;

use PoP\CacheControl\ComponentConfiguration;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
final class CacheControlDirectiveResolver extends \PoP\CacheControl\DirectiveResolvers\AbstractCacheControlDirectiveResolver implements \PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface
{
    /**
     * It must execute after everyone else!
     */
    public static function getPriorityToAttachClasses() : ?int
    {
        return 0;
    }
    /**
     * Do add this directive to the schema
     *
     * @return void
     */
    public function skipAddingToSchemaDefinition() : bool
    {
        return \false;
    }
    /**
     * The default max-age is configured through an environment variable
     *
     * @return integer|null
     */
    public function getMaxAge() : ?int
    {
        return \PoP\CacheControl\ComponentConfiguration::getDefaultCacheControlMaxAge();
    }
}
