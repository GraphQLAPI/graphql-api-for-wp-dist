<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\VarExporter;

interface LazyObjectInterface
{
    /**
     * Returns whether the object is initialized.
     *
     * @param $partial Whether partially initialized objects should be considered as initialized
     * @param bool $partial
     */
    public function isLazyObjectInitialized($partial = \false) : bool;
    /**
     * Forces initialization of a lazy object and returns it.
     * @return object
     */
    public function initializeLazyObject();
    /**
     * @return bool Returns false when the object cannot be reset, ie when it's not a lazy object
     */
    public function resetLazyObject() : bool;
}
