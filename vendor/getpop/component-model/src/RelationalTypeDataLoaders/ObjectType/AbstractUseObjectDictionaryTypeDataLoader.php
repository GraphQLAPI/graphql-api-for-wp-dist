<?php

declare (strict_types=1);
namespace PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType;

use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\AbstractObjectTypeDataLoader;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
abstract class AbstractUseObjectDictionaryTypeDataLoader extends AbstractObjectTypeDataLoader
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
        $objectDictionary = $this->getObjectDictionary();
        $objectTypeResolverClass = \get_class($this->getObjectTypeResolver());
        $objects = [];
        foreach ($ids as $id) {
            if (!$objectDictionary->has($objectTypeResolverClass, $id)) {
                $objectDictionary->set($objectTypeResolverClass, $id, $this->getObjectTypeNewInstance($id));
            }
            $objects[] = $objectDictionary->get($objectTypeResolverClass, $id);
        }
        return $objects;
    }
    protected abstract function getObjectTypeResolver() : ObjectTypeResolverInterface;
    /**
     * @param int|string $id
     * @return mixed
     */
    protected abstract function getObjectTypeNewInstance($id);
}
