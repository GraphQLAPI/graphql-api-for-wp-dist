<?php

/*
 * This file is part of the Cortex package.
 *
 * (c) Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrefixedByPoP\Brain\Cortex\Controller;

/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license http://opensource.org/licenses/MIT MIT
 * @package Cortex
 */
final class QueryVarsController implements ControllerInterface
{
    /**
     * @inheritdoc
     * @param mixed[] $vars
     * @param \WP $wp
     */
    public function run($vars, $wp, $template = '')
    {
        $wp->query_vars = $vars;
        return \false;
    }
}
