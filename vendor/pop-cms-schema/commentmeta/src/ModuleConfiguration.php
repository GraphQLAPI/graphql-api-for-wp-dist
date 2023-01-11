<?php

declare (strict_types=1);
namespace PoPCMSSchema\CommentMeta;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    /**
     * @return string[]
     */
    public function getCommentMetaEntries() : array
    {
        $envVariable = \PoPCMSSchema\CommentMeta\Environment::COMMENT_META_ENTRIES;
        $defaultValue = [];
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'commaSeparatedStringToArray']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getCommentMetaBehavior() : string
    {
        $envVariable = \PoPCMSSchema\CommentMeta\Environment::COMMENT_META_BEHAVIOR;
        $defaultValue = Behaviors::ALLOWLIST;
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue);
    }
}
