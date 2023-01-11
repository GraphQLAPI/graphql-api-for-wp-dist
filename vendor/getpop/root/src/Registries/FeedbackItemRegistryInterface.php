<?php

declare (strict_types=1);
namespace PoP\Root\Registries;

use PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface;
interface FeedbackItemRegistryInterface
{
    /**
     * @param \PoP\Root\FeedbackItemProviders\FeedbackItemProviderInterface $feedbackItemProvider
     */
    public function useFeedbackItemProvider($feedbackItemProvider) : void;
    /**
     * @return array<string,array<string,string>> [key] Namespaced code, [value] Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...]
     */
    public function getFeedbackItemEntries() : array;
    /**
     * @return mixed[]|null Array of ['category' => ..., 'messagePlaceholder' => ..., 'specifiedByURL' => ...], or null if no entry exists for that code
     * @param string $namespacedCode
     */
    public function getFeedbackItemEntry($namespacedCode) : ?array;
}
