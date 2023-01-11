<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\TypeAPIs;

use PoP\Root\App;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Users\Constants\UserOrderBy;
use PoPCMSSchema\Users\TypeAPIs\AbstractUserTypeAPI;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use WP_User;
use WP_User_Query;

use function get_user_by;
use function get_users;
use function esc_sql;
use function get_userdata;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class UserTypeAPI extends AbstractUserTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';
    public const HOOK_ORDERBY_QUERY_ARG_VALUE = __CLASS__ . ':orderby-query-arg-value';

    /**
     * Indicates if the passed object is of type User
     * @param object $object
     */
    public function isInstanceOfUserType($object): bool
    {
        return $object instanceof WP_User;
    }

    /**
     * @param string|int $propertyValue
     * @param string $property
     */
    protected function getUserBy($property, $propertyValue)
    {
        $user = get_user_by($property, $propertyValue);
        if ($user === false) {
            return null;
        }
        return $user;
    }

    /**
     * @param string|int $userID
     */
    public function getUserByID($userID)
    {
        return $this->getUserBy('id', $userID);
    }

    /**
     * @param string $email
     */
    public function getUserByEmail($email)
    {
        return $this->getUserBy('email', $email);
    }

    /**
     * @param string $login
     */
    public function getUserByLogin($login)
    {
        return $this->getUserBy('login', $login);
    }

    /**
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getUserCount($query, $options = []): int
    {
        // Convert the parameters
        $options[QueryOptions::RETURN_TYPE] = ReturnTypes::IDS;
        $query = $this->convertUsersQuery($query, $options);

        // All results, no offset
        $query['number'] = -1;
        unset($query['offset']);

        // Limit users which have an email appearing on the input
        // WordPress does not allow to search by many email addresses, only 1!
        // Then we implement a hack to allow for it:
        // 1. Set field "search", as expected
        // 2. Add a hook which will modify the SQL query
        // 3. Execute query
        // 4. Remove hook
        $filterByEmails = $this->filterByEmails($query);
        if ($filterByEmails) {
            App::addAction('pre_user_query', \Closure::fromCallable([$this, 'enableMultipleEmails']));
        }

        // Execute the query. Original solution from:
        // @see https://developer.wordpress.org/reference/functions/get_users/#source
        // Only difference: use `total_count` => true, `get_total` instead of `get_results`
        $args                = \wp_parse_args($query);
        $args['count_total'] = true;
        $user_search = new \WP_User_Query($args);
        $ret = (int) $user_search->get_total();

        // Remove the hook
        if ($filterByEmails) {
            App::removeAction('pre_user_query', \Closure::fromCallable([$this, 'enableMultipleEmails']));
        }
        return $ret;
    }
    /**
     * @return array<string|int>|object[]
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function getUsers($query, $options = []): array
    {
        // Convert the parameters
        $query = $this->convertUsersQuery($query, $options);

        // Limit users which have an email appearing on the input
        // WordPress does not allow to search by many email addresses, only 1!
        // Then we implement a hack to allow for it:
        // 1. Set field "search", as expected
        // 2. Add a hook which will modify the SQL query
        // 3. Execute query
        // 4. Remove hook
        $filterByEmails = $this->filterByEmails($query);
        if ($filterByEmails) {
            App::addAction('pre_user_query', \Closure::fromCallable([$this, 'enableMultipleEmails']));
        }

        // Execute the query
        $ret = get_users($query);

        // Remove the hook
        if ($filterByEmails) {
            App::removeAction('pre_user_query', \Closure::fromCallable([$this, 'enableMultipleEmails']));
        }
        return $ret;
    }

    /**
     * Limit users which have an email appearing on the input
     * WordPress does not allow to search by many email addresses, only 1!
     * Then we implement a hack to allow for it:
     * 1. Set field "search", as expected
     * 2. Add a hook which will modify the SQL query
     * 3. Execute query
     * 4. Remove hook
     *
     * @param array<string,mixed> $query
     *
     * @see https://developer.wordpress.org/reference/classes/wp_user_query/#search-parameters
     */
    protected function filterByEmails(&$query): bool
    {
        if (isset($query['emails'])) {
            $emails = $query['emails'];
            // This works for either 1 or many emails
            $query['search'] = implode(',', $emails);
            $query['search_columns'] = ['user_email'];
            // But if there's more than 1 email, we must modify the SQL query with a hook
            if (count($emails) > 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    protected function convertUsersQuery($query, $options = []): array
    {
        if (($options[QueryOptions::RETURN_TYPE] ?? null) === ReturnTypes::IDS) {
            $query['fields'] = 'ID';
        }

        // Convert parameters
        if (isset($query['name'])) {
            $query['meta_query'][] = [
                'key' => 'nickname',
                'value' => $query['name'],
                'compare' => 'LIKE',
            ];
            unset($query['name']);
        }
        if (isset($query['username'])) {
            $query['login'] = $query['username'];
            unset($query['username']);
        }
        /**
         * Watch out: "search" and "emails" can't be set at the same time,
         * because they both use the same "search" field in the query.
         */
        if (isset($query['search']) && !($query['emails'] ?? null)) {
            // Search: Attach "*" before/after the term, to support searching partial strings
            $query['search'] = sprintf(
                '*%s*',
                $query['search']
            );
        }
        if (isset($query['include']) && is_array($query['include'])) {
            // It can be an array or a string
            $query['include'] = implode(',', $query['include']);
        }
        if (isset($query['exclude-ids'])) {
            $query['exclude'] = $query['exclude-ids'];
            unset($query['exclude-ids']);
        }
        if (isset($query['order'])) {
            $query['order'] = esc_sql($query['order']);
        }
        if (isset($query['orderby'])) {
            // Maybe replace the provided value
            $query['orderby'] = esc_sql($this->getOrderByQueryArgValue($query['orderby']));
        }
        if (isset($query['offset'])) {
            // Same param name, so do nothing
        }
        if (isset($query['limit'])) {
            $limit = (int) $query['limit'];
            $query['number'] = $limit;
            unset($query['limit']);
        }

        return App::applyFilters(
            self::HOOK_QUERY,
            $query,
            $options
        );
    }
    /**
     * @param string $orderBy
     */
    protected function getOrderByQueryArgValue($orderBy): string
    {
        switch ($orderBy) {
            case UserOrderBy::ID:
                $orderBy = 'ID';
                break;
            case UserOrderBy::NAME:
                $orderBy = 'name';
                break;
            case UserOrderBy::USERNAME:
                $orderBy = 'login';
                break;
            case UserOrderBy::DISPLAY_NAME:
                $orderBy = 'display_name';
                break;
            case UserOrderBy::REGISTRATION_DATE:
                $orderBy = 'registered';
                break;
            default:
                $orderBy = $orderBy;
                break;
        }
        return App::applyFilters(
            self::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $orderBy
        );
    }
    /**
     * Modify the SQL query, replacing searching for a single email
     * (with SQL operation "=") to multiple ones (with SQL operation "IN")
     * @param \WP_User_Query $query
     */
    public function enableMultipleEmails($query): void
    {
        $qv =& $query->query_vars;
        if (isset($qv['search'])) {
            $search = trim($qv['search']);
            // Validate it has no wildcards, it's email (because there's a "@")
            // and there's more than one (because there's ",")
            $leading_wild = (ltrim($search, '*') != $search);
            $trailing_wild = (rtrim($search, '*') != $search);
            if (!$leading_wild && !$trailing_wild && false !== strpos($search, '@') && false !== strpos($search, ',')) {
                // Replace the query
                $emails = explode(',', $search);
                $searches = [sprintf("user_email IN (%s)", "'" . implode("','", $emails) . "'")];
                $replace = $this->get_search_sql($search, ['user_email'], false);
                $replacement = ' AND (' . implode(' OR ', $searches) . ')';
                $query->query_where = str_replace($replace, $replacement, $query->query_where);
            }
        }
    }

    /**
     * Function copied from WordPress source, because it is protected!
     *
     * @source wp-includes/class-wp-user-query.php
     *
     * Used internally to generate an SQL string for searching across multiple columns
     *
     * @since 3.1.0
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     *
     * @param string $string
     * @param array  $cols
     * @param bool   $wild   Whether to allow wildcard searches. Default is false for Network Admin, true for single site.
     *                       Single site allows leading and trailing wildcards, Network Admin only trailing.
     * @return string
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_search_sql($string, $cols, $wild = false)
    {
        global $wpdb;

        $searches      = array();
        // Avoid PHPStan errors:
        //   Result of || is always false
        // $leading_wild  = ( 'leading' === $wild || 'both' === $wild ) ? '%' : '';
        // $trailing_wild = ( 'trailing' === $wild || 'both' === $wild ) ? '%' : '';
        $leading_wild  = '';
        $trailing_wild = '';
        $like          = $leading_wild . $wpdb->esc_like($string) . $trailing_wild;

        foreach ($cols as $col) {
            if ('ID' === $col) {
                $searches[] = $wpdb->prepare("$col = %s", $string);
            } else {
                $searches[] = $wpdb->prepare("$col LIKE %s", $like);
            }
        }

        return ' AND (' . implode(' OR ', $searches) . ')';
    }

    /**
     * @param string|int|object $userObjectOrID
     * @return mixed
     * @param string $property
     */
    protected function getUserProperty($property, $userObjectOrID)
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
        } else {
            $userID = $userObjectOrID;
            $user = get_userdata((int)$userID);
            if ($user === false) {
                return null;
            }
        }
        return $user->$property;
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserDisplayName($userObjectOrID): ?string
    {
        return $this->getUserProperty('display_name', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserEmail($userObjectOrID): ?string
    {
        return $this->getUserProperty('user_email', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserFirstname($userObjectOrID): ?string
    {
        return $this->getUserProperty('user_firstname', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserLastname($userObjectOrID): ?string
    {
        return $this->getUserProperty('user_lastname', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserLogin($userObjectOrID): ?string
    {
        return $this->getUserProperty('user_login', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserDescription($userObjectOrID): ?string
    {
        return $this->getUserProperty('description', $userObjectOrID);
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserWebsiteURL($userObjectOrID): ?string
    {
        $userURL = $this->getUserProperty('user_url', $userObjectOrID);
        if (empty($userURL)) {
            return null;
        }
        return $userURL;
    }
    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserSlug($userObjectOrID): ?string
    {
        return $this->getUserProperty('user_nicename', $userObjectOrID);
    }
    /**
     * @return string|int
     * @param object $user
     */
    public function getUserID($user)
    {
        /** @var WP_User $user */
        return $user->ID;
    }

    /**
     * @param string|int|object $userObjectOrID
     */
    public function getUserURL($userObjectOrID): ?string
    {
        if (is_object($userObjectOrID)) {
            /** @var WP_User */
            $user = $userObjectOrID;
            $userID = $user->ID;
        } else {
            $userID = (int)$userObjectOrID;
        }
        return get_author_posts_url($userID);
    }
}
