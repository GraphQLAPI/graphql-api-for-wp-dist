<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Symfony\Component\ExpressionLanguage\Node;

use PrefixedByPoP\Symfony\Component\ExpressionLanguage\Compiler;
/**
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal
 */
class NullCoalesceNode extends Node
{
    public function __construct(Node $expr1, Node $expr2)
    {
        parent::__construct(['expr1' => $expr1, 'expr2' => $expr2]);
    }
    /**
     * @param \Symfony\Component\ExpressionLanguage\Compiler $compiler
     */
    public function compile($compiler)
    {
        $compiler->raw('((')->compile($this->nodes['expr1'])->raw(') ?? (')->compile($this->nodes['expr2'])->raw('))');
    }
    /**
     * @param mixed[] $functions
     * @param mixed[] $values
     */
    public function evaluate($functions, $values)
    {
        if ($this->nodes['expr1'] instanceof GetAttrNode) {
            $this->nodes['expr1']->attributes['is_null_coalesce'] = \true;
        }
        return $this->nodes['expr1']->evaluate($functions, $values) ?? $this->nodes['expr2']->evaluate($functions, $values);
    }
    public function toArray()
    {
        return ['(', $this->nodes['expr1'], ') ?? (', $this->nodes['expr2'], ')'];
    }
}
