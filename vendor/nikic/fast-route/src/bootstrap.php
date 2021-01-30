<?php

namespace PrefixedByPoP\FastRoute;

require __DIR__ . '/functions.php';
\spl_autoload_register(function ($class) {
    if (\strpos($class, 'PrefixedByPoP\\FastRoute\\') === 0) {
        $name = \substr($class, \strlen('PrefixedByPoP\\FastRoute'));
        require __DIR__ . \strtr($name, '\\', \DIRECTORY_SEPARATOR) . '.php';
    }
});
