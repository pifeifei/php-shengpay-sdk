<?php

namespace Pff\Client\Traits;

use Pff\Client\Filter\ClientFilter;
use Pff\Client\Exception\ClientException;

/**
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
