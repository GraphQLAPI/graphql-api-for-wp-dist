<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\Messenger;

use PrefixedByPoP\Psr\Log\LoggerInterface;
use PrefixedByPoP\Symfony\Component\Cache\Adapter\AdapterInterface;
use PrefixedByPoP\Symfony\Component\Cache\CacheItem;
use PrefixedByPoP\Symfony\Component\DependencyInjection\ReverseContainer;
use PrefixedByPoP\Symfony\Component\Messenger\MessageBusInterface;
use PrefixedByPoP\Symfony\Component\Messenger\Stamp\HandledStamp;
/**
 * Sends the computation of cached values to a message bus.
 */
class EarlyExpirationDispatcher
{
    /**
     * @var \Symfony\Component\Messenger\MessageBusInterface
     */
    private $bus;
    /**
     * @var \Symfony\Component\DependencyInjection\ReverseContainer
     */
    private $reverseContainer;
    /**
     * @var \Closure|null
     */
    private $callbackWrapper;
    public function __construct(MessageBusInterface $bus, ReverseContainer $reverseContainer, callable $callbackWrapper = null)
    {
        $this->bus = $bus;
        $this->reverseContainer = $reverseContainer;
        $this->callbackWrapper = null === $callbackWrapper ? null : \Closure::fromCallable($callbackWrapper);
    }
    public function __invoke(callable $callback, CacheItem $item, bool &$save, AdapterInterface $pool, \Closure $setMetadata, LoggerInterface $logger = null)
    {
        if (!$item->isHit() || null === ($message = EarlyExpirationMessage::create($this->reverseContainer, $callback, $item, $pool))) {
            // The item is stale or the callback cannot be reversed: we must compute the value now
            ($logger2 = $logger) ? $logger2->info('Computing item "{key}" online: ' . ($item->isHit() ? 'callback cannot be reversed' : 'item is stale'), ['key' => $item->getKey()]) : null;
            return null !== $this->callbackWrapper ? ($this->callbackWrapper)($callback, $item, $save, $pool, $setMetadata, $logger) : $callback($item, $save);
        }
        $envelope = $this->bus->dispatch($message);
        if ($logger) {
            if ($envelope->last(HandledStamp::class)) {
                $logger->info('Item "{key}" was computed online', ['key' => $item->getKey()]);
            } else {
                $logger->info('Item "{key}" sent for recomputation', ['key' => $item->getKey()]);
            }
        }
        // The item's value is not stale, no need to write it to the backend
        $save = \false;
        return $message->getItem()->get() ?? $item->get();
    }
}
