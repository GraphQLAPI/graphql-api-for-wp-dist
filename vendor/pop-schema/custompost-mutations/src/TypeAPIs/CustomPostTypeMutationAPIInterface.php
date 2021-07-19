<?php

declare (strict_types=1);
namespace PoPSchema\CustomPostMutations\TypeAPIs;

use PoP\ComponentModel\ErrorHandling\Error;
/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface CustomPostTypeMutationAPIInterface
{
    /**
     * @param array<string, mixed> $data
     * @return string|int|null|\PoP\ComponentModel\ErrorHandling\Error the ID of the created custom post, or null or Error if none was created
     */
    public function createCustomPost(array $data);
    /**
     * @param array<string, mixed> $data
     * @return string|int|null|\PoP\ComponentModel\ErrorHandling\Error the ID of the updated custom post, or null or Error if the post did not exist
     */
    public function updateCustomPost(array $data);
    /**
     * @param string|int $userID
     * @param string|int $customPostID
     */
    public function canUserEditCustomPost($userID, $customPostID) : bool;
}
