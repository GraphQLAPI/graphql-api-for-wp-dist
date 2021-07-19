<?php

declare (strict_types=1);
namespace PoP\ComponentModel\TypeDataLoaders;

use PoP\ComponentModel\Facades\Container\ObjectDictionaryFacade;
trait UseObjectDictionaryTypeDataLoaderTrait
{
    public function getObjects(array $ids) : array
    {
        $objectDictionary = ObjectDictionaryFacade::getInstance();
        $typeResolverClass = $this->getTypeResolverClass();
        $ret = [];
        foreach ($ids as $id) {
            if (!$objectDictionary->has($typeResolverClass, $id)) {
                $objectDictionary->set($typeResolverClass, $id, $this->getTypeNewInstance($id));
            }
            $ret[] = $objectDictionary->get($typeResolverClass, $id);
        }
        return $ret;
    }
    protected abstract function getTypeResolverClass() : string;
    /**
     * @param int|string $id
     */
    protected abstract function getTypeNewInstance($id);
}
