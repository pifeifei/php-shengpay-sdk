<?php

namespace Pff\ShengPay\Request;

use Exception;
use Pff\Client\Exception\ClientException;

/**
 * Class SharingRpcRequest
 *
 * @package Pff\ShengPay
 */
class SharingRpcRequest extends RpcRequest
{
    /**
     * 分账
     * @param $sharing
     * @return SharingRpcRequest
     * @throws ClientException
     */
    public function sharing($sharing)
    {
//        ShengPayFilter::productName($sharing['productName']);

        $this->path('/api-gateway/sharing/api/v1/sharing');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $sharing]);

        return $this;
    }

    /**
     * 文件分账
     * @throws ClientException
     */
    public function fileNotify()
    {
        // /api-gateway/sharing/api/v1/file_notify
        throw new ClientException('TODO: 文件分账', 1);
    }

    /**
     * 分账查询
     * @param $sharingQuery
     * @return SharingRpcRequest
     * @throws ClientException
     */
    public function sharingQuery($sharingQuery)
    {
        $this->path('/api-gateway/sharing/api/v1/sharing_inquiry');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $sharingQuery]);

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     * @throws ClientException
     */
    public function sharingRetry($data)
    {
        $this->path('/api-gateway/sharing/api/v1/sharing_item');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $data]);

        return $this;
    }

    /**
     * 分账
     *
     * @param $refund
     * @return SharingRpcRequest
     * @throws ClientException
     */
    public function refund($refund)
    {
//        ShengPayFilter::productName($refund['productName']);

        $this->path('/api-gateway/sharing/api/v1/refund');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $refund]);

        return $this;
    }

    /**
     * 分账结果查询
     *
     * @param $refundQuery
     * @return SharingRpcRequest
     * @throws ClientException
     */
    public function refundQuery($refundQuery)
    {
//        ShengPayFilter::productName($refundQuery['productName']);

        $this->path('/api-gateway/sharing/api/v1/refund_inquiry');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $refundQuery]);

        return $this;
    }

    /**
     * 对失败的退款重试
     *
     * @param $refundQuery
     * @return SharingRpcRequest
     * @throws ClientException
     */
    public function refundRetry($refundQuery)
    {
//        ShengPayFilter::productName($refundQuery['productName']);

        $this->path('/api-gateway/sharing/api/v1/refund_item');
        $this->product('shengpay-sharing')
            ->method('POST')
            ->options(['query' => $refundQuery]);

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
        if ($this->credential()->getAccessKeyId()) {
            $this->options['query']['senderId'] = $this->credential()->getAccessKeyId();
            $this->options['query']['customerNo'] = $this->credential()->getAccessKeyId();
        }
        $this->options['query']['senderTime']    = date($this->dateTimeFormat);

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
                $this->options['json'][$key] = $value;
            }
            unset($this->options['query']);
        }
        ksort($this->options['json']);
    }

    /**
     * @return string
     */
    public function stringToSign()
    {
        $parameters  = $this->getParameters();
        ksort($parameters);
        return json_encode($parameters);
    }

//账户余额查询: /v1/balance_inquiry
//补充商户余额: /v1/funds_movement
}
