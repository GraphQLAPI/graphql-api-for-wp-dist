<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\DependencyInjection\Loader\Configurator;

use PrefixedByPoP\Symfony\Component\DependencyInjection\Definition;
use PrefixedByPoP\Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
/**
 * @author Nicolas Grekas <p@tchwork.com>
 */
class DefaultsConfigurator extends AbstractServiceConfigurator
{
    use Traits\AutoconfigureTrait;
    use Traits\AutowireTrait;
    use Traits\BindTrait;
    use Traits\PublicTrait;
    public const FACTORY = 'defaults';
    /**
     * @var string|null
     */
    private $path;
    public function __construct(ServicesConfigurator $parent, Definition $definition, string $path = null)
    {
        parent::__construct($parent, $definition, null, []);
        $this->path = $path;
    }
    /**
     * Adds a tag for this definition.
     *
     * @return $this
     *
     * @throws InvalidArgumentException when an invalid tag name or attribute is provided
     * @param string $name
     * @param mixed[] $attributes
     */
    public final function tag($name, $attributes = [])
    {
        if ('' === $name) {
            throw new InvalidArgumentException('The tag name in "_defaults" must be a non-empty string.');
        }
        $this->validateAttributes($name, $attributes);
        $this->definition->addTag($name, $attributes);
        return $this;
    }
    /**
     * Defines an instanceof-conditional to be applied to following service definitions.
     * @param string $fqcn
     */
    public final function instanceof($fqcn) : InstanceofConfigurator
    {
        return $this->parent->instanceof($fqcn);
    }
    private function validateAttributes(string $tagName, array $attributes, string $prefix = '') : void
    {
        foreach ($attributes as $attribute => $value) {
            if (\is_array($value)) {
                $this->validateAttributes($tagName, $value, $attribute . '.');
            } elseif (!\is_scalar($value ?? '')) {
                throw new InvalidArgumentException(\sprintf('Tag "%s", attribute "%s" in "_defaults" must be of a scalar-type or an array of scalar-type.', $tagName, $prefix . $attribute));
            }
        }
    }
}
