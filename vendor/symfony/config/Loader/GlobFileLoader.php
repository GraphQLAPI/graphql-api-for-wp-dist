<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\Config\Loader;

/**
 * GlobFileLoader loads files from a glob pattern.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class GlobFileLoader extends FileLoader
{
    /**
     * @param mixed $resource
     * @return mixed
     * @param string|null $type
     */
    public function load($resource, $type = null)
    {
        return $this->import($resource);
    }
    /**
     * @param mixed $resource
     * @param string|null $type
     */
    public function supports($resource, $type = null) : bool
    {
        return 'glob' === $type;
    }
}
