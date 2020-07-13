<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Support\Arrays;

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
     * 秘钥文件路径
     * @var string
     */
    protected $privateKeyFile;
    /**
     * 秘钥字符串
     * @var string
     */
    protected $privateKey;

    /**
     * @return string
     */
    public function getPrivateKeyFile()
    {
        return $this->privateKeyFile;
    }

    /**
     * @param string $privateKeyFile
     * @return UserAccountRpcRequest
     */
    public function setPrivateKeyFile($privateKeyFile)
    {
        $this->privateKeyFile = $privateKeyFile;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateKey()
    {
        if (empty($this->privateKey) && file_exists($this->getPrivateKeyFile())) {
            $this->privateKey = file_get_contents($this->getPrivateKeyFile());
        }
        return $this->privateKey;
    }

    /**
     * @param string $privateKey
     * @return UserAccountRpcRequest
     */
    public function setPrivateKey($privateKey)
    {
        $this->privateKey = $privateKey;
        return $this;
    }

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
     * @throws ServerException
     */
    public function query($query)
    {
        if ($this->credential()->getAccessKeyId()) {
            $query['merchantNo'] = $this->credential()->getAccessKeyId();
        }
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
     * Resolve Common Parameters.
     *
     * @throws ClientException
     * @throws Exception
     */
    protected function resolveCommonParameters()
    {
        if (strtoupper($this->method) !== 'GET' && $this->credential()->getAccessKeyId()) {
            $this->options['query']['merchantNo'] = $this->credential()->getAccessKeyId();
        }

        $this->options['query']['sign'] = $this->signature();
        // 其他公共参数
    }

    /**
     * Sign the parameters.
     *
     * @return mixed
     * @throws ClientException
     * @throws ServerException
     */
    protected function signature()
    {
        return $this->httpClient()
            ->getSignature()
            ->sign(
                $this->stringToSign(),
                $this->getPrivateKey() ? $this->getPrivateKey() : $this->credential()->getAccessKeySecret()
            );
    }

    /**
     * @return string
     */
    public function stringToSign()
    {
        $query       = isset($this->options['query']) ? $this->options['query'] : [];
        $form_params = isset($this->options['form_params']) ? $this->options['form_params'] : [];
        $parameters  = Arrays::merge([$query, $form_params]);
        ksort($parameters);
        $sign = http_build_query($parameters);
        if ($this->isUserAccount()) {
            unset($parameters['merchantNo'], $parameters['userName']);
            unset($this->options['query']['merchantNo'], $this->options['query']['userName']);
        }

        return $sign;
//        return Sign::rpcString($this->method, $parameters);
    }

    protected function isUserAccount()
    {
        return $this->method === 'GET' && false !== strpos($this->uri->getPath(), "userAccount/");
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
