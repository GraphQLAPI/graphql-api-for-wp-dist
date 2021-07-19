<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Attribute;

/**
 * @annotation
 */
class TaggedIterator
{
    /**
     * @var string
     */
    public $tag;
    /**
     * @var string|null
     */
    public $indexAttribute;
    public function __construct(string $tag, ?string $indexAttribute = null)
    {
        $this->tag = $tag;
        $this->indexAttribute = $indexAttribute;
    }
}
