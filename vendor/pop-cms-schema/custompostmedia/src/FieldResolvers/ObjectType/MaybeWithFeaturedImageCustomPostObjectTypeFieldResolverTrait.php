<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMedia\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMedia\TypeAPIs\CustomPostMediaTypeAPIInterface;
trait MaybeWithFeaturedImageCustomPostObjectTypeFieldResolverTrait
{
    protected abstract function getCustomPostMediaTypeAPI() : CustomPostMediaTypeAPIInterface;
    public function isServiceEnabled() : bool
    {
        return $this->isFeaturedImageEnabledForCustomPostType();
    }
    protected function isFeaturedImageEnabledForCustomPostType() : bool
    {
        return $this->getCustomPostMediaTypeAPI()->doesCustomPostTypeSupportFeaturedImage($this->getCustomPostType());
    }
    protected abstract function getCustomPostType() : string;
}
