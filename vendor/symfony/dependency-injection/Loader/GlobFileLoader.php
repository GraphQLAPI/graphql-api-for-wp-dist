<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Loader;

/**
 * GlobFileLoader loads files from a glob pattern.
 *
 * @author Nicolas Grekas <p@tchwork.com>
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
        foreach ($this->glob($resource, \false, $globResource) as $path => $info) {
            $this->import($path);
        }
        $this->container->addResource($globResource);
        return null;
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
