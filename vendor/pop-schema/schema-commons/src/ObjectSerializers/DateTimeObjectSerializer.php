<?php

declare (strict_types=1);
namespace PoPSchema\SchemaCommons\ObjectSerializers;

use DateTime;
// @todo Replace with \DateTimeInterface. See: https://github.com/leoloso/PoP/issues/1282
use PoPSchema\SchemaCommons\Polyfill\PHP72\DateTimeInterface;
use PoP\ComponentModel\ObjectSerialization\AbstractObjectSerializer;
use stdClass;
class DateTimeObjectSerializer extends AbstractObjectSerializer
{
    public function getObjectClassToSerialize() : string
    {
        return DateTime::class;
    }
    /**
     * @return string|\stdClass
     * @param object $object
     */
    public function serialize($object)
    {
        /** @var DateTime $object */
        return $object->format(DateTimeInterface::ATOM);
    }
}
