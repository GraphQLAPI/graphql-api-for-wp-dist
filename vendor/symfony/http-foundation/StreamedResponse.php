<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\HttpFoundation;

/**
 * StreamedResponse represents a streamed HTTP response.
 *
 * A StreamedResponse uses a callback for its content.
 *
 * The callback should use the standard PHP functions like echo
 * to stream the response back to the client. The flush() function
 * can also be used if needed.
 *
 * @see flush()
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class StreamedResponse extends Response
{
    protected $callback;
    protected $streamed;
    /**
     * @var bool
     */
    private $headersSent;
    /**
     * @param int $status The HTTP status code (200 "OK" by default)
     */
    public function __construct(callable $callback = null, int $status = 200, array $headers = [])
    {
        parent::__construct(null, $status, $headers);
        if (null !== $callback) {
            $this->setCallback($callback);
        }
        $this->streamed = \false;
        $this->headersSent = \false;
    }
    /**
     * Sets the PHP callback associated with this Response.
     *
     * @return $this
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }
    /**
     * This method only sends the headers once.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        if ($this->headersSent) {
            return $this;
        }
        $this->headersSent = \true;
        return parent::sendHeaders();
    }
    /**
     * This method only sends the content once.
     *
     * @return $this
     */
    public function sendContent()
    {
        if ($this->streamed) {
            return $this;
        }
        $this->streamed = \true;
        if (null === $this->callback) {
            throw new \LogicException('The Response callback must not be null.');
        }
        ($this->callback)();
        return $this;
    }
    /**
     * @return $this
     *
     * @throws \LogicException when the content is not null
     * @param string|null $content
     */
    public function setContent($content)
    {
        if (null !== $content) {
            throw new \LogicException('The content cannot be set on a StreamedResponse instance.');
        }
        $this->streamed = \true;
        return $this;
    }
    /**
     * @return string|true
     */
    public function getContent()
    {
        return \false;
    }
}
