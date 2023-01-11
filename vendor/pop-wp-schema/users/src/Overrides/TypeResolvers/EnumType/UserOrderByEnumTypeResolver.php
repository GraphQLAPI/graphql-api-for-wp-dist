<?php

declare(strict_types=1);

namespace PoPWPSchema\Users\Overrides\TypeResolvers\EnumType;

use PoP\Root\App;
use PoPCMSSchema\Users\Module;
use PoPCMSSchema\Users\ModuleConfiguration;
use PoPCMSSchema\Users\TypeResolvers\EnumType\UserOrderByEnumTypeResolver as UpstreamUserOrderByEnumTypeResolver;
use PoPWPSchema\Users\Constants\UserOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_user_query/#search-parameters
 */
class UserOrderByEnumTypeResolver extends UpstreamUserOrderByEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getSensitiveEnumValues(): array
    {
        $adminEnumValues = parent::getSensitiveEnumValues();
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->treatUserEmailAsSensitiveData()) {
            $adminEnumValues[] = UserOrderBy::EMAIL;
        }
        return $adminEnumValues;
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                UserOrderBy::INCLUDE,
                UserOrderBy::WEBSITE_URL,
                UserOrderBy::NICENAME,
                UserOrderBy::EMAIL,
            ]
        );
    }

    /**
     * @param string $enumValue
     */
    public function getEnumValueDescription($enumValue): ?string
    {
        switch ($enumValue) {
            case UserOrderBy::INCLUDE:
                return $this->__('Order by the included list of user IDs (requires the \'ids\' parameter)', 'users');
            case UserOrderBy::WEBSITE_URL:
                return $this->__('Order by user\'s website URL', 'users');
            case UserOrderBy::NICENAME:
                return $this->__('Order by user nicename', 'users');
            case UserOrderBy::EMAIL:
                return $this->__('Order by user email', 'users');
            default:
                return parent::getEnumValueDescription($enumValue);
        }
    }
}
