<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType;

class RootSetTagsOnPostMutationPayloadObjectTypeResolver extends \PoPCMSSchema\PostTagMutations\TypeResolvers\ObjectType\AbstractPostTagsMutationPayloadObjectTypeResolver
{
    public function getTypeName() : string
    {
        return 'RootSetTagsOnPostMutationPayload';
    }
    public function getTypeDescription() : ?string
    {
        return $this->__('Payload of setting tags on a post', 'posttag-mutations');
    }
}
