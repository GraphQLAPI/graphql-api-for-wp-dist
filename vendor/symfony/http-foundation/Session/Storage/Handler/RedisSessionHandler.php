<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation\Session\Storage\Handler;

use PrefixedByPoP\Predis\Response\ErrorInterface;
/**
 * Redis based session storage handler based on the Redis class
 * provided by the PHP redis extension.
 *
 * @author Dalibor Karlović <dalibor@flexolabs.io>
 */
class RedisSessionHandler extends AbstractSessionHandler
{
    /**
     * Key prefix for shared environments.
     * @var string
     */
    private $prefix;
    /**
     * Time to live in seconds.
     * @var int|\Closure|null
     */
    private $ttl;
    /**
     * @var \Redis|\RedisArray|\RedisCluster|\Predis\ClientInterface
     */
    private $redis;
    /**
     * List of available options:
     *  * prefix: The prefix to use for the keys in order to avoid collision on the Redis server
     *  * ttl: The time to live in seconds.
     *
     * @throws \InvalidArgumentException When unsupported client or options are passed
     * @param \Redis|\RedisArray|\RedisCluster|\Predis\ClientInterface $redis
     */
    public function __construct($redis, array $options = [])
    {
        $this->redis = $redis;
        if ($diff = \array_diff(\array_keys($options), ['prefix', 'ttl'])) {
            throw new \InvalidArgumentException(\sprintf('The following options are not supported "%s".', \implode(', ', $diff)));
        }
        $this->prefix = $options['prefix'] ?? 'sf_s';
        $this->ttl = $options['ttl'] ?? null;
    }
    /**
     * @param string $sessionId
     */
    protected function doRead($sessionId) : string
    {
        return $this->redis->get($this->prefix . $sessionId) ?: '';
    }
    /**
     * @param string $sessionId
     * @param string $data
     */
    protected function doWrite($sessionId, $data) : bool
    {
        $ttl = ($this->ttl instanceof \Closure ? ($this->ttl)() : $this->ttl) ?? \ini_get('session.gc_maxlifetime');
        $result = $this->redis->setEx($this->prefix . $sessionId, (int) $ttl, $data);
        return $result && !$result instanceof ErrorInterface;
    }
    /**
     * @param string $sessionId
     */
    protected function doDestroy($sessionId) : bool
    {
        static $unlink = \true;
        if ($unlink) {
            try {
                $unlink = \false !== $this->redis->unlink($this->prefix . $sessionId);
            } catch (\Throwable $exception) {
                $unlink = \false;
            }
        }
        if (!$unlink) {
            $this->redis->del($this->prefix . $sessionId);
        }
        return \true;
    }
    #[\ReturnTypeWillChange]
    public function close() : bool
    {
        return \true;
    }
    /**
     * @return int|true
     */
    public function gc(int $maxlifetime)
    {
        return 0;
    }
    public function updateTimestamp(string $sessionId, string $data) : bool
    {
        $ttl = ($this->ttl instanceof \Closure ? ($this->ttl)() : $this->ttl) ?? \ini_get('session.gc_maxlifetime');
        return $this->redis->expire($this->prefix . $sessionId, (int) $ttl);
    }
}
