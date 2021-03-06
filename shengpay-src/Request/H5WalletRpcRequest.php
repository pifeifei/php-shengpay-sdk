<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;

/**
 * H5 钱包
 * @package Pff\ShengPay\Request
 */
class H5WalletRpcRequest extends RpcRequest
{
    /* @var bool */
    protected $sandbox = false;

    /* @var string */
    protected $host = 'wmc.shengpay.com';

    /* @var string */
    protected $hostSandbox = 'wmctest.shengpay.com';

    /**
     * @return bool
     */
    public function inSandbox()
    {
        return (bool) $this->sandbox;
    }

    /**
     * 设置在测试环境中运行
     *
     * @param bool $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * 自动设置域名
     * @return $this
     * @throws ClientException
     */
    protected function autoHostOrSandboxHost()
    {
        if ($this->inSandbox()) {
            $this->host($this->hostSandbox);
        } else {
            $this->host($this->host);
        }
        return $this;
    }

    /**
     * 钱包用户xxx TODO: xx
     * @param $sharing
     * @return $this
     * @throws ClientException
     * @see http://docs.shengpay.com/7.1.H5%E9%92%B1%E5%8C%85.html
     */
    public function register($sharing)
    {
        $this->path($this->wrap('member/register'));
        $this->product('shengpay-user-account')
            ->method('POST')
            ->options(['query' => $sharing]);

        return $this;
    }

    /**
     * Wrapping an API endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     */
    protected function wrap($endpoint)
    {
        return '/' . ltrim($endpoint, '/');
    }

    /**
     * Resolve Common Parameters.
     *
     * @throws ClientException
     * @throws Exception
     */
    protected function resolveCommonParameters()
    {
        if ($this->credential()->getAccessKeyId()) {
            $this->options['query']['merchantNo'] = $this->credential()->getAccessKeyId();
        }

        $signature = $this->httpClient()->getSignature();
        $this->options['query']['signType'] = $signature->getMethod();
        $this->options['query']['sign'] = $this->signature();
        // 其他公共参数
    }

    /**
     * @return string
     */
    public function stringToSign()
    {
        $parameters  = $this->getParameters();
        ksort($parameters);
        return http_build_query($parameters);
    }
}
