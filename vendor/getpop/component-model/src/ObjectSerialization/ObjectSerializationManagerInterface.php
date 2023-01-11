<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectSerialization;

use stdClass;
interface ObjectSerializationManagerInterface
{
    /**
     * @param \PoP\ComponentModel\ObjectSerialization\ObjectSerializerInterface $objectSerializer
     */
    public function addObjectSerializer($objectSerializer) : void;
    /**
     * @return string|\stdClass
     * @param object $object
     */
    public function serialize($object);
}
