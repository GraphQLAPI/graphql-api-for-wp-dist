<?php

declare (strict_types=1);
namespace PoPCMSSchema\Comments;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function getRootCommentListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Comments\Environment::ROOT_COMMENT_LIST_DEFAULT_LIMIT;
        $defaultValue = 10;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getCustomPostCommentOrCommentResponseListDefaultLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Comments\Environment::CUSTOMPOST_COMMENT_OR_COMMENT_RESPONSE_LIST_DEFAULT_LIMIT;
        $defaultValue = -1;
        // Unlimited
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function getCommentListMaxLimit() : ?int
    {
        $envVariable = \PoPCMSSchema\Comments\Environment::COMMENT_LIST_MAX_LIMIT;
        $defaultValue = -1;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toInt']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function treatCommentStatusAsSensitiveData() : bool
    {
        $envVariable = \PoPCMSSchema\Comments\Environment::TREAT_COMMENT_STATUS_AS_SENSITIVE_DATA;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
    public function enableCommentsForGenericCustomPosts() : bool
    {
        $envVariable = \PoPCMSSchema\Comments\Environment::ENABLE_COMMENTS_FOR_GENERIC_CUSTOMPOSTS;
        $defaultValue = \true;
        $callback = \Closure::fromCallable([EnvironmentValueHelpers::class, 'toBool']);
        return $this->retrieveConfigurationValueOrUseDefault($envVariable, $defaultValue, $callback);
    }
}
