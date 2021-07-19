<?php

namespace PrefixedByPoP;

#[Attribute(\Attribute::TARGET_METHOD)]
final class ReturnTypeWillChange
{
    public function __construct()
    {
    }
}
\class_alias('PrefixedByPoP\\ReturnTypeWillChange', 'ReturnTypeWillChange', \false);
