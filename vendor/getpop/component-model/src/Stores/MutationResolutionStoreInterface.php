<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Stores;

interface MutationResolutionStoreInterface
{
    /**
     * @param mixed $result
     * @param object $object
     */
    public function setResult($object, $result) : void;
    /**
     * @return mixed
     * @param object $object
     */
    public function getResult($object);
}
