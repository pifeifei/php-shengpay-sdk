<?php

namespace Pff\Client\Traits;

use InvalidArgumentException;
use Pff\Client\AlibabaCloud;
use Pff\Client\Config\Config;
use Pff\Client\Request\Request;
use Pff\Client\Filter\ApiFilter;
use Pff\Client\Filter\HttpFilter;
use Pff\Client\Filter\ClientFilter;
use Pff\Client\Regions\LocationService;
use Pff\Client\Exception\ClientException;

/**
 * Help developers set up and get host.
 *
 * @package Pff\Client\Traits
 *
 * @mixin     AlibabaCloud
 */
trait EndpointTrait
{
    /**
     * @var array Host cache.
     */
    private static $hosts = [];

    /**
     * Resolve host based on product name and region.
     *
     * @param string $product
     * @param string $regionId
     *
     * @return string
     * @throws ClientException
     */
    public static function resolveHost($product, $regionId = LocationService::GLOBAL_REGION)
    {
        ApiFilter::product($product);
        ClientFilter::regionId($regionId);

        if (isset(self::$hosts[$product][$regionId])) {
            return self::$hosts[$product][$regionId];
        }

        $domain = Config::get("endpoints.{$product}.{$regionId}");
        if (!$domain) {
            $regionId = LocationService::GLOBAL_REGION;
            $domain   = Config::get("endpoints.{$product}.{$regionId}", '');
        }

        return $domain;
    }

    /**
     * @param string $product
     * @param string $regionId
     *
     * @return string
     */
    public static function resolveHostByStatic($product, $regionId)
    {
        if (isset(self::$hosts[$product][$regionId])) {
            return self::$hosts[$product][$regionId];
        }

        return '';
    }

    /**
     * Add host based on product name and region.
     *
     * @param string $product
     * @param string $host
     * @param string $regionId
     *
     * @return void
     * @throws ClientException
     */
    public static function addHost($product, $host, $regionId = LocationService::GLOBAL_REGION)
    {
        ApiFilter::product($product);

        HttpFilter::host($host);

        ClientFilter::regionId($regionId);

        self::$hosts[$product][$regionId] = $host;

        LocationService::addHost($product, $host, $regionId);
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws ClientException
     */
    public static function resolveHostByRule(Request $request)
    {
        $regionId = $request->realRegionId();
        $network  = $request->network ?: 'public';
        $suffix   = $request->endpointSuffix;
        if ($network === 'public') {
            $network = '';
        }

        if ($request->endpointRegional === 'regional') {
            return "{$request->product}{$suffix}{$network}.{$regionId}.aliyuncs.com";
        }

        if ($request->endpointRegional === 'central') {
            return "{$request->product}{$suffix}{$network}.aliyuncs.com";
        }

        throw new InvalidArgumentException('endpointRegional is invalid.');
    }
}
