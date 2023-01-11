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

/**
 * Can be used in unit testing or in a situations where persisted sessions are not desired.
 *
 * @author Drak <drak@zikula.org>
 */
class NullSessionHandler extends AbstractSessionHandler
{
    public function close() : bool
    {
        return \true;
    }
    public function validateId(string $sessionId) : bool
    {
        return \true;
    }
    /**
     * @param string $sessionId
     */
    protected function doRead($sessionId) : string
    {
        return '';
    }
    public function updateTimestamp(string $sessionId, string $data) : bool
    {
        return \true;
    }
    /**
     * @param string $sessionId
     * @param string $data
     */
    protected function doWrite($sessionId, $data) : bool
    {
        return \true;
    }
    /**
     * @param string $sessionId
     */
    protected function doDestroy($sessionId) : bool
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
}
