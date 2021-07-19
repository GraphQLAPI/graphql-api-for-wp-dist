<?php

declare (strict_types=1);
namespace PoPSchema\Categories\ComponentContracts;

use PoPSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
trait CategoryAPIRequestedContractTrait
{
    protected abstract function getTypeAPI() : CategoryTypeAPIInterface;
    protected abstract function getTypeResolverClass() : string;
}
