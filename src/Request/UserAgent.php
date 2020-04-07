<?php

namespace Pff\Client\Request;

use Pff\Client\AlibabaCloud;
use Pff\Client\Filter\Filter;
use Pff\Client\Support\Arrays;
use Pff\Client\Exception\ClientException;

/**
 * Class UserAgent
 *
 * @package Pff\Client\Request
 */
class UserAgent
{

    /**
     * @var array
     */
    private static $userAgent = [];

    /**
     * @var array
     */
    private static $guard = [
        'client',
        'php',
    ];

    /**
     * @param array $append
     *
     * @return string
     */
    public static function toString(array $append = [])
    {
        self::defaultFields();

        $os        = \PHP_OS;
        $osVersion = php_uname('r');
        $osMode    = php_uname('m');
        $userAgent = "pff client ($os $osVersion; $osMode) ";

        $newUserAgent = [];

        $append = self::clean($append);

        $append = Arrays::merge(
            [
                self::$userAgent,
                $append,
            ]
        );

        foreach ($append as $key => $value) {
            if ($value === null) {
                $newUserAgent[] = $key;
                continue;
            }
            $newUserAgent[] = "$key/$value";
        }

        return $userAgent . \implode(' ', $newUserAgent);
    }

    /**
     * UserAgent constructor.
     */
    private static function defaultFields()
    {
        if (self::$userAgent === []) {
            self::$userAgent = [
                'Client' => AlibabaCloud::VERSION,
                'PHP'    => \PHP_VERSION,
            ];
        }
    }

    /**
     * @param array $append
     *
     * @return array
     */
    public static function clean(array $append)
    {
        foreach ($append as $key => $value) {
            if (self::isGuarded($key)) {
                unset($append[$key]);
                continue;
            }
        }

        return $append;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public static function isGuarded($name)
    {
        return in_array(strtolower($name), self::$guard, true);
    }

    /**
     * set User Agent of Alibaba Cloud.
     *
     * @param string $name
     * @param string $value
     *
     * @throws ClientException
     */
    public static function append($name, $value)
    {
        Filter::name($name);
        Filter::value($value);

        self::defaultFields();

        if (!self::isGuarded($name)) {
            self::$userAgent[$name] = $value;
        }
    }

    /**
     * @param array $userAgent
     */
    public static function with(array $userAgent)
    {
        self::$userAgent = self::clean($userAgent);
    }

    /**
     * Clear all of the User Agent.
     */
    public static function clear()
    {
        self::$userAgent = [];
    }
}
