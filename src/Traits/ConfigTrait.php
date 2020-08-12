<?php

namespace Pff\Client\Traits;

use Pff\Client\Clients\AccessKeyClient;

trait ConfigTrait
{
    /**
     * 配置信息
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     * @return $this
     */
    public function config($config)
    {
        $this->config = $config;
        return $this;
    }

    public function getConfig($key = null)
    {
        if (is_null($key)) {
            return $this->config;
        }

        return isset($this->config[$key]) ? $this->config[$key] : null;
    }
}
