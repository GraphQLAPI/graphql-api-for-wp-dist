<?php

declare(strict_types=1);

namespace PoP\EngineWP\Hooks;

use PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

class TemplateHookSet extends AbstractHookSet
{
    /**
     * @var \PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface|null
     */
    private $applicationStateHelperService;
    /**
     * @var \PoP\EngineWP\HelperServices\TemplateHelpersInterface|null
     */
    private $templateHelpers;

    /**
     * @param \PoP\ComponentModel\HelperServices\ApplicationStateHelperServiceInterface $applicationStateHelperService
     */
    final public function setApplicationStateHelperService($applicationStateHelperService): void
    {
        $this->applicationStateHelperService = $applicationStateHelperService;
    }
    final protected function getApplicationStateHelperService(): ApplicationStateHelperServiceInterface
    {
        /** @var ApplicationStateHelperServiceInterface */
        return $this->applicationStateHelperService = $this->applicationStateHelperService ?? $this->instanceManager->getInstance(ApplicationStateHelperServiceInterface::class);
    }
    /**
     * @param \PoP\EngineWP\HelperServices\TemplateHelpersInterface $templateHelpers
     */
    final public function setTemplateHelpers($templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        /** @var TemplateHelpersInterface */
        return $this->templateHelpers = $this->templateHelpers ?? $this->instanceManager->getInstance(TemplateHelpersInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            'template_include',
            \Closure::fromCallable([$this, 'getTemplate']),
            PHP_INT_MAX // Execute last
        );
    }

    /**
     * @param string $template
     */
    public function getTemplate($template): string
    {
        if ($this->useTemplate()) {
            return $this->getTemplateHelpers()->getGenerateDataAndPrepareAndSendResponseTemplateFile();
        }
        return $template;
    }

    /**
     * If doing JSON, return the template which prints the encoded JSON
     */
    protected function useTemplate(): bool
    {
        return $this->getApplicationStateHelperService()->doingJSON();
    }
}
