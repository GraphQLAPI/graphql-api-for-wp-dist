<?php

namespace PrefixedByPoP;

// Don't redefine the functions if included multiple times.
if (!\function_exists('PrefixedByPoP\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
