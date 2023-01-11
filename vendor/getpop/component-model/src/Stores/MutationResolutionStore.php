<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Stores;

class MutationResolutionStore implements \PoP\ComponentModel\Stores\MutationResolutionStoreInterface
{
    /**
     * @var array<string,mixed>
     */
    private $results = [];
    /**
     * @param mixed $result
     * @param object $object
     */
    public function setResult($object, $result) : void
    {
        $this->results[\get_class($object)] = $result;
    }
    /**
     * @return mixed
     * @param object $object
     */
    public function getResult($object)
    {
        return $this->results[\get_class($object)];
    }
}
