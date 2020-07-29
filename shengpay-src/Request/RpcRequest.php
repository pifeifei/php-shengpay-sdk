<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Request\RpcRequest as ClientRpcRequest;

class RpcRequest extends ClientRpcRequest
{

    private $dateTimeFormat = 'YmdHis';

    private $charset = 'UTF-8';

    /**
     * @var string 货币类型
     */
    private $currency = 'CNY';

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
        $this->options['query']['charset']        = $this->charset;
        $this->options['query']['requestTime']    = date($this->dateTimeFormat);
        if (isset($this->options['query']['exts']) && is_array($this->options['query']['exts'])) {
            $this->options['query']['exts'] = json_encode($this->options['query']['exts']);
        }

        $signature = $this->httpClient()->getSignature();
        $this->options['headers']['signType'] = $signature->getMethod();
        $this->options['headers']['signMsg'] = $this->signature();
        // 其他公共参数
    }

    /**
     * Adjust parameter position
     */
    protected function repositionParameters()
    {
        if ($this->method === 'POST' || $this->method === 'PUT') {
            foreach ($this->options['query'] as $api_key => $api_value) {
                $this->options['json'][$api_key] = $api_value;
            }
            unset($this->options['query']);
        }
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
                $this->credential()->getAccessKeySecret()
            );
    }

    protected function getClientIp()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function realRegionId()
    {
        return $this->regionId = '';
    }
}
