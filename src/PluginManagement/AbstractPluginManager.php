<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginManagement;

abstract class AbstractPluginManager
{
    /**
     * @param string $errorMessage
     */
    protected function printAdminNoticeErrorMessage($errorMessage): void
    {
        \add_action('admin_notices', function () use ($errorMessage): void {
            _e(sprintf(
                '<div class="notice notice-error">' .
                    '<p>%s</p>' .
                '</div>',
                $errorMessage
            ));
        });
    }
}
