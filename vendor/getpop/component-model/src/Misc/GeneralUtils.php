<?php

declare (strict_types=1);
namespace PoP\ComponentModel\Misc;

class GeneralUtils
{
    /**
     * @see Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
     * @param int $length
     * @param bool $addtime
     * @param string $characters
     */
    public static function generateRandomString($length = 6, $addtime = \true, $characters = 'abcdefghijklmnopqrstuvwxyz') : string
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[\rand(0, \strlen($characters) - 1)];
        }
        if ($addtime) {
            $randomString .= \time();
        }
        return $randomString;
    }
    /**
     * @return mixed[]
     *
     * @see https://gist.github.com/SeanCannon/6585889
     * @param mixed $items
     * @param bool $deep
     */
    public static function arrayFlatten($items, $deep = \false) : array
    {
        if (!\is_array($items)) {
            return [$items];
        }
        return \array_reduce($items, function ($carry, $item) use($deep) : array {
            return \array_merge($carry, $deep ? self::arrayFlatten($item) : (\is_array($item) ? $item : [$item]));
        }, []);
    }
    /**
     * Add paramters "key" => "value" to the URL
     *
     * @param array<string,string> $keyValues
     * @see https://stackoverflow.com/a/5809881
     * @param string $urlOrURLPath
     */
    public static function addQueryArgs($keyValues, $urlOrURLPath) : string
    {
        if (!$keyValues) {
            return $urlOrURLPath;
        }
        $url_parts = \parse_url($urlOrURLPath);
        if (!\is_array($url_parts)) {
            return $urlOrURLPath;
        }
        $params = [];
        if (isset($url_parts['query'])) {
            \parse_str($url_parts['query'], $params);
        }
        $params = \array_merge($params, $keyValues);
        // Note that this will url_encode all values
        $query = \http_build_query($params);
        // Check if schema/host are present, becase the URL can also be a relative path: /some-path/
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        $port = isset($url_parts['port']) && $url_parts['port'] ? $url_parts['port'] == "80" ? "" : ":" . $url_parts['port'] : '';
        $path = $url_parts['path'] ?? '';
        return $scheme . $host . $port . $path . ($query ? '?' . $query : '');
    }
    /**
     * Add paramters "key" => "value" to the URL
     *
     * @param string[] $keys
     * @see https://stackoverflow.com/a/5809881
     * @param string $urlOrURLPath
     */
    public static function removeQueryArgs($keys, $urlOrURLPath) : string
    {
        if (!$keys) {
            return $urlOrURLPath;
        }
        $url_parts = \parse_url($urlOrURLPath);
        if (!\is_array($url_parts)) {
            return $urlOrURLPath;
        }
        /** @var array<string,mixed> */
        $params = [];
        if (isset($url_parts['query'])) {
            \parse_str($url_parts['query'], $params);
        }
        // Remove the indicated keys
        $params = \array_filter($params, function (string $param) use($keys) : bool {
            return \in_array($param, $keys);
        }, \ARRAY_FILTER_USE_KEY);
        // Note that this will url_encode all values
        $query = \http_build_query($params);
        // Check if schema/host are present, becase the URL can also be a relative path: /some-path/
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        $port = isset($url_parts['port']) && $url_parts['port'] ? $url_parts['port'] == "80" ? "" : ":" . $url_parts['port'] : '';
        $path = $url_parts['path'] ?? '';
        return $scheme . $host . $port . $path . ($query ? '?' . $query : '');
    }
    /**
     * @param string $text
     */
    public static function maybeAddTrailingSlash($text) : string
    {
        return \rtrim($text, '/\\') . '/';
    }
    /**
     * @param string $url
     */
    public static function getDomain($url) : string
    {
        $url_parts = \parse_url($url);
        if (!\is_array($url_parts)) {
            return $url;
        }
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        return $scheme . $host;
    }
    /**
     * @param string $url
     */
    public static function removeDomain($url) : string
    {
        return \substr($url, \strlen(self::getDomain($url)));
    }
    /**
     * @param string $url
     */
    public static function getPath($url) : string
    {
        $url_parts = \parse_url($url);
        if (!\is_array($url_parts)) {
            return $url;
        }
        $path = $url_parts['path'] ?? '';
        return $path;
    }
    /**
     * @param iterable<mixed> $iterable
     * @return mixed[]
     */
    public static function iterableToArray($iterable) : array
    {
        if (\is_array($iterable)) {
            return $iterable;
        }
        $array = [];
        \array_push($array, ...$iterable);
        return $array;
    }
    /**
     * @return array<string,mixed>
     * @param string $url
     */
    public static function getURLQueryParams($url) : array
    {
        $queryParams = [];
        if ($queryString = \parse_url($url, \PHP_URL_QUERY)) {
            \parse_str($queryString, $queryParams);
        }
        /** @var array<string,mixed> */
        return $queryParams;
    }
}
