<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectSerialization;

use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;
class ObjectSerializationManager implements \PoP\ComponentModel\ObjectSerialization\ObjectSerializationManagerInterface
{
    use BasicServiceTrait;
    /**
     * @var array<string,ObjectSerializerInterface>
     */
    public $objectSerializers = [];
    /**
     * @param \PoP\ComponentModel\ObjectSerialization\ObjectSerializerInterface $objectSerializer
     */
    public final function addObjectSerializer($objectSerializer) : void
    {
        $this->objectSerializers[$objectSerializer->getObjectClassToSerialize()] = $objectSerializer;
    }
    /**
     * @return string|\stdClass
     * @param object $object
     */
    public function serialize($object)
    {
        // Find the Serialize that serializes this object
        $objectSerializer = null;
        /** @var string|false */
        $classToSerialize = \get_class($object);
        while ($objectSerializer === null && $classToSerialize !== \false) {
            $objectSerializer = $this->objectSerializers[$classToSerialize] ?? null;
            $classToSerialize = \get_parent_class($classToSerialize);
        }
        if ($objectSerializer !== null) {
            return $objectSerializer->serialize($object);
        }
        /**
         * No Serializer found. Then call the '__serialize' method of the object,
         * expecting it to implement it. If it doesn't, it will throw an exception,
         * so the developer will be made aware to create the corresponding Serializer
         * for that object class
         */
        if (!\method_exists($object, '__serialize')) {
            throw new ShouldNotHappenException(\sprintf($this->__('The object of class \'%s\' does not support method \'__serialize\'', 'component-model'), \get_class($object)));
        }
        return $object->__serialize();
    }
}
