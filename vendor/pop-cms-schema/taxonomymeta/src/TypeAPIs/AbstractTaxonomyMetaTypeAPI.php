<?php

declare (strict_types=1);
namespace PoPCMSSchema\TaxonomyMeta\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPCMSSchema\TaxonomyMeta\Module;
use PoPCMSSchema\TaxonomyMeta\ModuleConfiguration;
abstract class AbstractTaxonomyMetaTypeAPI extends AbstractMetaTypeAPI implements \PoPCMSSchema\TaxonomyMeta\TypeAPIs\TaxonomyMetaTypeAPIInterface
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
    public final function getTaxonomyTermMeta($termObjectOrID, $key, $single = \false, $options = [])
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetTaxonomyMeta($termObjectOrID, $key, $single);
    }
    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries() : array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getTaxonomyMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior() : string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getTaxonomyMetaBehavior();
    }
    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     * @param string|int|object $termObjectOrID
     * @return mixed
     * @param string $key
     * @param bool $single
     */
    protected abstract function doGetTaxonomyMeta($termObjectOrID, $key, $single = \false);
}
