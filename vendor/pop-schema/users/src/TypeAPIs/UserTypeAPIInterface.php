<?php

declare (strict_types=1);
namespace PoPSchema\Users\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface UserTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type User
     * @param object $object
     */
    public function isInstanceOfUserType($object) : bool;
    /**
     * @param string|int $userID
     * @return object|null
     */
    public function getUserById($userID);
    /**
     * @return object|null
     */
    public function getUserByEmail(string $email);
    /**
     * @return object|null
     */
    public function getUserByLogin(string $login);
    public function getUsers($query = array(), array $options = []) : array;
    public function getUserCount(array $query = [], array $options = []) : int;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserDisplayName($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserEmail($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserFirstname($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserLastname($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserLogin($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserDescription($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserURL($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserWebsiteUrl($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserSlug($userObjectOrID) : ?string;
    /**
     * @return string|int
     * @param object $user
     */
    public function getUserId($user);
}
