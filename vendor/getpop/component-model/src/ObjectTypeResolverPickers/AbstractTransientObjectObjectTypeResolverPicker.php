<?php

declare (strict_types=1);
namespace PoP\ComponentModel\ObjectTypeResolverPickers;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
abstract class AbstractTransientObjectObjectTypeResolverPicker extends \PoP\ComponentModel\ObjectTypeResolverPickers\AbstractObjectTypeResolverPicker
{
    /**
     * @var \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface|null
     */
    private $objectDictionary;
    /**
     * @param \PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface $objectDictionary
     */
    public final function setObjectDictionary($objectDictionary) : void
    {
        $this->objectDictionary = $objectDictionary;
    }
    protected final function getObjectDictionary() : ObjectDictionaryInterface
    {
        /** @var ObjectDictionaryInterface */
        return $this->objectDictionary = $this->objectDictionary ?? $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
    }
    /**
     * @param object $object
     */
    public final function isInstanceOfType($object) : bool
    {
        return \is_a($object, $this->getTargetObjectClass(), \true);
    }
    protected abstract function getTargetObjectClass() : string;
    /**
     * @param string|int $objectID
     */
    public final function isIDOfType($objectID) : bool
    {
        $transientObject = $this->getObjectDictionary()->get($this->getTargetObjectClass(), $objectID);
        if ($transientObject === null) {
            return \false;
        }
        return $this->isInstanceOfType($transientObject);
    }
}
