<?php

declare (strict_types=1);
namespace PoPCMSSchema\TaxonomyMeta\TypeAPIs;

use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\MetaTypeAPIInterface;
interface TaxonomyMetaTypeAPIInterface extends MetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws MetaKeyNotAllowedException
     * @param string|int|object $termObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    public function getTaxonomyTermMeta($termObjectOrID, $key, $single = \false, $options = []);
}
