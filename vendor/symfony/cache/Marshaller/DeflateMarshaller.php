<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Cache\Marshaller;

use PrefixedByPoP\Symfony\Component\Cache\Exception\CacheException;
/**
 * Compresses values using gzdeflate().
 *
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DeflateMarshaller implements MarshallerInterface
{
    /**
     * @var \Symfony\Component\Cache\Marshaller\MarshallerInterface
     */
    private $marshaller;
    public function __construct(MarshallerInterface $marshaller)
    {
        if (!\function_exists('gzdeflate')) {
            throw new CacheException('The "zlib" PHP extension is not loaded.');
        }
        $this->marshaller = $marshaller;
    }
    /**
     * @param mixed[] $values
     * @param mixed[]|null $failed
     */
    public function marshall($values, &$failed) : array
    {
        return \array_map('gzdeflate', $this->marshaller->marshall($values, $failed));
    }
    /**
     * @return mixed
     * @param string $value
     */
    public function unmarshall($value)
    {
        if (\false !== ($inflatedValue = @\gzinflate($value))) {
            $value = $inflatedValue;
        }
        return $this->marshaller->unmarshall($value);
    }
}
