<?php

declare (strict_types=1);
namespace PoP\LooseContracts;

interface LooseContractManagerInterface
{
    /**
     * @return string[]
     */
    public function getNotImplementedRequiredNames() : array;
    /**
     * @param string[] $names
     */
    public function requireNames($names) : void;
    /**
     * @param string[] $names
     */
    public function implementNames($names) : void;
}
