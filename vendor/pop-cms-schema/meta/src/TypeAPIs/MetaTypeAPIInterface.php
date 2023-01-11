<?php

declare (strict_types=1);
namespace PoPCMSSchema\Meta\TypeAPIs;

interface MetaTypeAPIInterface
{
    /**
     * @param string $key
     */
    public function validateIsMetaKeyAllowed($key) : bool;
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries() : array;
    public function getAllowOrDenyMetaBehavior() : string;
}
