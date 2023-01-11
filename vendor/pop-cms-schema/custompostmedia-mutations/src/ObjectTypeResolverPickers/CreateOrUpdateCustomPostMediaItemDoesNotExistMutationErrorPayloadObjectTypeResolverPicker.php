<?php

declare (strict_types=1);
namespace PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\CustomPostMutations\TypeResolvers\UnionType\AbstractCustomPostMutationErrorPayloadUnionTypeResolver;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
class CreateOrUpdateCustomPostMediaItemDoesNotExistMutationErrorPayloadObjectTypeResolverPicker extends \PoPCMSSchema\CustomPostMediaMutations\ObjectTypeResolverPickers\AbstractMediaItemDoesNotExistErrorPayloadObjectTypeResolverPicker
{
    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo() : array
    {
        return [AbstractCustomPostMutationErrorPayloadUnionTypeResolver::class];
    }
}
