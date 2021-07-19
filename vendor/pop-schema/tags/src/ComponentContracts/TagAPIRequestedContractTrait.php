<?php

declare (strict_types=1);
namespace PoPSchema\Tags\ComponentContracts;

use PoPSchema\Tags\TypeAPIs\TagTypeAPIInterface;
trait TagAPIRequestedContractTrait
{
    protected abstract function getTypeAPI() : TagTypeAPIInterface;
    protected abstract function getTypeResolverClass() : string;
}
