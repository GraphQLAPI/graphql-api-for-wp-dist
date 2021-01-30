<?php

declare (strict_types=1);
namespace PoP\Engine\Hooks\Misc;

use PoP\Engine\Environment;
use PoP\Hooks\AbstractHookSet;
use PoP\Definitions\Facades\DefinitionManagerFacade;
class DefinitionPersistenceHookSet extends \PoP\Hooks\AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction('popcms:shutdown', array($this, 'maybePersist'));
    }
    public function maybePersist()
    {
        if (!\PoP\Engine\Environment::disablePersistingDefinitionsOnEachRequest()) {
            \PoP\Definitions\Facades\DefinitionManagerFacade::getInstance()->maybeStoreDefinitionsPersistently();
        }
    }
}
