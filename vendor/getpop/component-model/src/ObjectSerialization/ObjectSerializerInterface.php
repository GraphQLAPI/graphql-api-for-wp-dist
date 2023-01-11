<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectSerialization;

use stdClass;
interface ObjectSerializerInterface
{
    public function getObjectClassToSerialize() : string;
    /**
     * @return string|\stdClass
     * @param object $object
     */
    public function serialize($object);
}
