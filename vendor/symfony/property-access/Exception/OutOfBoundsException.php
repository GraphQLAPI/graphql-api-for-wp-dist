<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\PropertyAccess\Exception;

/**
 * Base OutOfBoundsException for the PropertyAccess component.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class OutOfBoundsException extends \OutOfBoundsException implements \PrefixedByPoP\Symfony\Component\PropertyAccess\Exception\ExceptionInterface
{
}
