<?php

declare (strict_types=1);
namespace PoPSchema\PostMutations\MutationResolvers;

use PoPSchema\CustomPostMutations\MutationResolvers\CreateCustomPostMutationResolverTrait;
class CreatePostMutationResolver extends \PoPSchema\PostMutations\MutationResolvers\AbstractCreateUpdatePostMutationResolver
{
    use CreateCustomPostMutationResolverTrait;
}
