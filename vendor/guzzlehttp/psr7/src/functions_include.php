<?php

namespace PrefixedByPoP;

// Don't redefine the functions if included multiple times.
if (!\function_exists('PrefixedByPoP\\GuzzleHttp\\Psr7\\str')) {
    require __DIR__ . '/functions.php';
}
