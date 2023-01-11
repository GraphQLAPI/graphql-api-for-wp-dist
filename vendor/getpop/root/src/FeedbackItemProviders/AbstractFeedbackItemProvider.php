<?php

declare (strict_types=1);
namespace PoP\Root\FeedbackItemProviders;

use PoP\Root\Exception\MisconfiguredServiceException;
use PoP\Root\Helpers\ClassHelpers;
use PoP\Root\Services\BasicServiceTrait;
abstract class AbstractFeedbackItemProvider implements \PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface
{
    use BasicServiceTrait;
    /**
     * @param string $code
     */
    public final function getNamespacedCode($code) : string
    {
        return $this->getNamespace() . $this->getNamespaceSeparator() . $code;
    }
    protected function getNamespace() : string
    {
        return \str_replace('\\', '/', ClassHelpers::getClassPSR4Namespace(\get_called_class()));
    }
    protected function getNamespaceSeparator() : string
    {
        return '@';
    }
    /**
     * @param string|int|float|bool|null ...$args
     * @param string $code
     */
    public final function getMessage($code, ...$args) : string
    {
        return \sprintf($this->getMessagePlaceholder($code), ...$args);
    }
    /**
     * @param string $code
     */
    public function getMessagePlaceholder($code) : string
    {
        throw new MisconfiguredServiceException(\sprintf($this->__('There is no message placeholder for code \'%s\'', 'root'), $code));
    }
    /**
     * @param string $code
     */
    public function getCategory($code) : string
    {
        throw new MisconfiguredServiceException(\sprintf($this->__('There is no category for code \'%s\'', 'root'), $code));
    }
    /**
     * @param string $code
     */
    public function getSpecifiedByURL($code) : ?string
    {
        return null;
    }
}
