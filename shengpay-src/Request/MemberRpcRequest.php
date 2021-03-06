<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;

/**
 * Class MemberRpcRequest
 * @package Pff\ShengPay\Request
 */
class MemberRpcRequest extends RpcRequest
{
    /**
     * @deprecated
     * @var bool
     */
    protected $sandbox = false;

    /**
     * @deprecated
     * @var string
     */
    protected $host = 'wmc.shengpay.com';

    /**
     * @deprecated
     * @var string
     */
    protected $hostSandbox = 'wmctest.shengpay.com';

    /**
     * @deprecated
     * @return bool
     */
    public function inSandbox()
    {
        return (bool) $this->sandbox;
    }

    /**
     * @deprecated
     * 设置在沙箱中运行
     *
     * @param bool $sandbox
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;
    }

    /**
     * @deprecated
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
     * 开通个人盛付通账户
     * @param $sharing
     * @return $this
     * @throws ClientException
     * @see https://docs.shengpay.com/4.1.%E9%9D%99%E9%BB%98%E5%BC%80%E6%88%B7.html
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
//        $this->autoHostOrSandboxHost();
        return ("cloud/v1/") . ltrim($endpoint, '/');
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
