<?php

namespace Pff\ShengPay\Request;

use Pff\Client\Exception\ClientException;
use Pff\ShengPay\Filter\ShengPayFilter;

/**
 * Class UserAccountRpcRequest
 * @package Pff\ShengPay\Request
 */
class UserAccountRpcRequest extends RpcRequest
{
    protected $sandbox = false;
    protected $host = 'api.shengpay.com';
    protected $hostSandbox = 'wdtest.shengpay.com';

    /**
     * @return bool
     */
    public function inSandbox()
    {
        return (bool) $this->sandbox;
    }

    /**
     * 设置在沙箱中运行
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
     * 开通个人盛付通账户
     * @param $sharing
     * @return $this
     * @throws ClientException
     */
    public function register($sharing)
    {
        $this->path($this->wrap('userAccount/register'));
        $this->product('shengpay-user-account')
            ->method('POST')
            ->options(['query' => $sharing]);

        return $this;
    }

    /**
     * 查询个人盛付通账户余额
     * @param $query
     * @return $this
     * @throws ClientException
     */
    public function query($query)
    {
        $uriStr = 'userAccount/{merchantNo}-{userName}';
        $uriStr = str_replace(
            ['{merchantNo}', '{userName}'],
            [$query['merchantNo'], $query['userName']],
            $uriStr
        );
        $this->path($this->wrap($uriStr));
        $this->product('shengpay-user-account')
            ->method('GET')
            ->options(['query' => $query]);

        return $this;
    }

    /**
     * 个人从其盛付通账户提现至银行卡
     * @param $query
     * @return $this
     * @throws ClientException
     */
    public function fundWithdraw($query)
    {
        $this->path($this->wrap('fund/withdraw'));
        $this->product('shengpay-user-account')
            ->method('POST')
            ->options(['query' => $query]);

        return $this;
    }

    /**
     * 查询提现订单信息
     * @param $query
     * @return $this
     * @throws ClientException
     */
    public function queryWithdraw($query)
    {
        $this->path($this->wrap('order/withdraw/query'));
        $this->product('shengpay-user-account')
            ->method('POST')
            ->options(['query' => $query]);

        return $this;
    }


    /**
     * Wrapping an API endpoint.
     *
     * @param string $endpoint
     *
     * @return string
     * @throws ClientException
     */
    protected function wrap($endpoint)
    {
        $this->autoHostOrSandboxHost();
        return ($this->inSandbox() ? "api/" : "wangdai/v1/") . ltrim($endpoint, '/');
    }
}
