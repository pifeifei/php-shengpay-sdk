<?php

namespace Pff\Client\Traits;

use Pff\Client\AlibabaCloud;
use Pff\Client\Filter\ClientFilter;
use Pff\Client\Exception\ClientException;

/**
 * Trait DefaultRegionTrait
 *
 * @package Pff\Client\Traits
 *
 * @mixin     AlibabaCloud
 */
trait DefaultRegionTrait
{
    /**
     * @var string|null Default RegionId
     */
    protected static $defaultRegionId;

    /**
     * @param $regionId
     *
     * @throws ClientException
     * @deprecated
     * @codeCoverageIgnore
     */
    public static function setGlobalRegionId($regionId)
    {
        self::setDefaultRegionId($regionId);
    }

    /**
     * @return string|null
     * @deprecated
     * @codeCoverageIgnore
     */
    public static function getGlobalRegionId()
    {
        return self::getDefaultRegionId();
    }

    /**
     * Get the default RegionId.
     *
     * @return string|null
     */
    public static function getDefaultRegionId()
    {
        return self::$defaultRegionId;
    }

    /**
     * Set the default RegionId.
     *
     * @param string $regionId
     *
     * @throws ClientException
     */
    public static function setDefaultRegionId($regionId)
    {
        self::$defaultRegionId = ClientFilter::regionId($regionId);
    }
}
