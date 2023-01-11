<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection;

use PrefixedByPoP\Psr\Container\ContainerInterface as PsrContainerInterface;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
/**
 * ContainerInterface is the interface implemented by service container classes.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
interface ContainerInterface extends PsrContainerInterface
{
    public const RUNTIME_EXCEPTION_ON_INVALID_REFERENCE = 0;
    public const EXCEPTION_ON_INVALID_REFERENCE = 1;
    public const NULL_ON_INVALID_REFERENCE = 2;
    public const IGNORE_ON_INVALID_REFERENCE = 3;
    public const IGNORE_ON_UNINITIALIZED_REFERENCE = 4;
    /**
     * @param string $id
     */
    public function set($id, $service);
    /**
     * @throws ServiceCircularReferenceException When a circular reference is detected
     * @throws ServiceNotFoundException          When the service is not defined
     *
     * @see Reference
     * @param string $id
     * @param int $invalidBehavior
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE);
    /**
     * @param string $id
     */
    public function has($id) : bool;
    /**
     * Check for whether or not a service has been initialized.
     * @param string $id
     */
    public function initialized($id) : bool;
    /**
     * @return array|bool|string|int|float|\UnitEnum|null
     *
     * @throws ParameterNotFoundException if the parameter is not defined
     * @param string $name
     */
    public function getParameter($name);
    /**
     * @param string $name
     */
    public function hasParameter($name) : bool;
    /**
     * @param mixed[]|bool|string|int|float|\UnitEnum|null $value
     * @param string $name
     */
    public function setParameter($name, $value);
}
