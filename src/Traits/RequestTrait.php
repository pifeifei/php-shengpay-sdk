<?php

namespace Pff\Client\Traits;

use Pff\Client\AlibabaCloud;
use Pff\Client\Filter\Filter;
use Pff\Client\Request\UserAgent;
use Pff\Client\Request\RpcRequest;
use Pff\Client\Request\RoaRequest;
use Pff\Client\Exception\ClientException;

/**
 * Trait RequestTrait
 *
 * @package Pff\Client\Traits
 *
 * @mixin     AlibabaCloud
 */
trait RequestTrait
{
    /**
     * @param string $name
     * @param string $value
     *
     * @throws ClientException
     */
    public static function appendUserAgent($name, $value)
    {
        Filter::name($name);
        Filter::value($value);

        UserAgent::append($name, $value);
    }

    /**
     * @param array $userAgent
     */
    public static function withUserAgent(array $userAgent)
    {
        UserAgent::with($userAgent);
    }

    /**
     * @param array $options
     *
     * @return RpcRequest
     * @throws ClientException
     * @deprecated
     * @codeCoverageIgnore
     */
    public static function rpcRequest(array $options = [])
    {
        return self::rpc($options);
    }

    /**
     * @param array $options
     *
     * @return RpcRequest
     * @throws ClientException
     */
    public static function rpc(array $options = [])
    {
        return new RpcRequest($options);
    }

    /**
     * @param array $options
     *
     * @return RoaRequest
     * @throws ClientException
     * @deprecated
     * @codeCoverageIgnore
     */
    public static function roaRequest(array $options = [])
    {
        return self::roa($options);
    }

    /**
     * @param array $options
     *
     * @return RoaRequest
     * @throws ClientException
     */
    public static function roa(array $options = [])
    {
        return new RoaRequest($options);
    }
}
