<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
interface CustomPostMutationResolverInterface extends \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
{
    public function getCustomPostType() : string;
}
