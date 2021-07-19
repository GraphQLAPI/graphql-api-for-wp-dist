<?php

declare (strict_types=1);
namespace PoPSchema\QueriedObject\Routing;

interface CMSRoutingStateServiceInterface
{
    /**
     * Get the currently queried object
     * @return object|null
     */
    public function getQueriedObject();
    /**
     * Get the ID of the currently queried object
     * @return string|int|null
     */
    public function getQueriedObjectId();
}
