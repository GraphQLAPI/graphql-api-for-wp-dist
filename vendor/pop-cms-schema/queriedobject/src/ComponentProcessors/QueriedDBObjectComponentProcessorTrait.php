<?php

declare (strict_types=1);
namespace PoPCMSSchema\QueriedObject\ComponentProcessors;

use PoP\Root\App;
trait QueriedDBObjectComponentProcessorTrait
{
    /**
     * @return string|int|null
     */
    protected function getQueriedDBObjectID()
    {
        return App::getState(['routing', 'queried-object-id']) ?? null;
    }
}
