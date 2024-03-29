<?php

declare (strict_types=1);
namespace PoPCMSSchema\PostMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\UpdateCustomPostMutationResolverTrait;
class UpdatePostMutationResolver extends \PoPCMSSchema\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver
{
    use UpdateCustomPostMutationResolverTrait;
}
