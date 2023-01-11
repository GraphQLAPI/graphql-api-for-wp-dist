<?php

declare (strict_types=1);
namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
abstract class AbstractDictionaryObjectTypeDataLoader extends \PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader
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
     * @param array<string|int> $ids
     * @return array<object|null>
     */
    public function getObjects($ids) : array
    {
        $objectClass = $this->getObjectClass();
        $objectDictionary = $this->getObjectDictionary();
        return \array_map(function ($id) use($objectDictionary, $objectClass) {
            return $objectDictionary->get($objectClass, $id);
        }, $ids);
    }
    protected abstract function getObjectClass() : string;
}
