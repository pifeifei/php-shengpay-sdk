<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Request\RpcRequest as ClientRpcRequest;
use Pff\Client\Support\Arrays;

class RpcRequest extends ClientRpcRequest
{

    protected $dateTimeFormat = 'YmdHis';

    protected $charset = 'UTF-8';

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
            foreach ($this->options['query'] as $key => $value) {
                $this->options['json'][$key] = is_array($value) ? json_encode($value) : $value;
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

    protected function getParameters()
    {
        $query       = isset($this->options['query']) ? $this->options['query'] : [];
        $form_params = isset($this->options['form_params']) ? $this->options['form_params'] : [];
        $json        = isset($this->options['json']) ? $this->options['json'] : [];
        return Arrays::merge([$query, $form_params, $json]);
    }
}
