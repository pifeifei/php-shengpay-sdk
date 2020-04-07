<?php

namespace Pff\Client\Filter;

use Pff\Client\SDK;
use Pff\Client\Exception\ClientException;

/**
 * Class ClientFilter
 *
 * @package Pff\Client\Filter
 */
class ClientFilter
{
    /**
     * @param $regionId
     *
     * @return string
     *
     */
    public static function regionId($regionId)
    {
        return strtolower((string)$regionId);
//        if (!is_string($regionId)) {
//            throw new ClientException(
//                'Region ID must be a string',
//                SDK::INVALID_ARGUMENT
//            );
//        }
//
//        if ($regionId === '') {
//            throw new ClientException(
//                'Region ID cannot be empty',
//                SDK::INVALID_ARGUMENT
//            );
//        }
//
//        return strtolower($regionId);
    }

    /**
     * @param $clientName
     *
     * @return string
     *
     * @throws ClientException
     */
    public static function clientName($clientName)
    {
        if (!is_string($clientName)) {
            throw new ClientException(
                'Client Name must be a string',
                SDK::INVALID_ARGUMENT
            );
        }

        if ($clientName === '') {
            throw new ClientException(
                'Client Name cannot be empty',
                SDK::INVALID_ARGUMENT
            );
        }

        return strtolower($clientName);
    }

    /**
     * @param $times
     *
     * @return string
     *
     * @throws ClientException
     */
    public static function retry($times)
    {
        if (!is_int($times)) {
            throw new ClientException(
                'Retry must be a int',
                SDK::INVALID_ARGUMENT
            );
        }

        return $times;
    }

    /**
     * @param $connectTimeout
     *
     * @return mixed
     * @throws ClientException
     */
    public static function connectTimeout($connectTimeout)
    {
        if ($connectTimeout === '') {
            throw new ClientException(
                'Connect Timeout cannot be empty',
                SDK::INVALID_ARGUMENT
            );
        }

        return $connectTimeout;
    }

    /**
     * @param $timeout
     *
     * @return mixed
     * @throws ClientException
     */
    public static function timeout($timeout)
    {
        if ($timeout === '') {
            throw new ClientException(
                'Timeout cannot be empty',
                SDK::INVALID_ARGUMENT
            );
        }

        return $timeout;
    }

    /**
     * @param int $Milliseconds
     *
     * @return mixed
     * @throws ClientException
     */
    public static function milliseconds($Milliseconds)
    {
        if (!is_int($Milliseconds)) {
            throw new ClientException(
                'Milliseconds must be int',
                SDK::INVALID_ARGUMENT
            );
        }

        return $Milliseconds;
    }
}
