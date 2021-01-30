<?php

namespace PrefixedByPoP;

use PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver;
class PoP_PostTags_Module_Processor_FieldDataloads extends \PrefixedByPoP\PoP_Tags_Module_Processor_FieldDataloads
{
    public function getTypeResolverClass(array $module) : ?string
    {
        switch ($module[1]) {
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAG:
            case self::MODULE_DATALOAD_RELATIONALFIELDS_TAGLIST:
                return \PoPSchema\PostTags\TypeResolvers\PostTagTypeResolver::class;
        }
        return parent::getTypeResolverClass($module);
    }
}
\class_alias('PrefixedByPoP\\PoP_PostTags_Module_Processor_FieldDataloads', 'PoP_PostTags_Module_Processor_FieldDataloads', \false);
