<?php

namespace Pff\Client\Traits;

use Pff\Client\Filter\ClientFilter;
use Pff\Client\Exception\ClientException;

/**
 * @deprecated
 * Trait RegionTrait
 *
 * @package Pff\Client\Traits
 */
trait RegionTrait
{

    /**
     * @var string|null
     */
    public $regionId;

    /**
     * @deprecated
     * @param string $regionId
     *
     * @return $this
     */
    public function regionId($regionId)
    {
//        $this->regionId = ClientFilter::regionId($regionId);

        return $this;
    }
}
