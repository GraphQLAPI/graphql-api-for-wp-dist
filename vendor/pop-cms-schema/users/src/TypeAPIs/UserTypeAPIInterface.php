<?php

declare (strict_types=1);
namespace PoPCMSSchema\Users\TypeAPIs;

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
     */
    public function getUserByID($userID);
    /**
     * @param string $email
     */
    public function getUserByEmail($email);
    /**
     * @param string $login
     */
    public function getUserByLogin($login);
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getUsers($query, $options = []) : array;
    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getUserCount($query, $options = []) : int;
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
    public function getUserURLPath($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserWebsiteURL($userObjectOrID) : ?string;
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserSlug($userObjectOrID) : ?string;
    /**
     * @return string|int
     * @param object $user
     */
    public function getUserID($user);
}
