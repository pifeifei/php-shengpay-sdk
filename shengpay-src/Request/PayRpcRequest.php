<?php

namespace Pff\ShengPay\Request;

use Pff\Client\Exception\ClientException;
use Pff\ShengPay\Filter\ShengPayFilter;

/**
 * Class PayRpcRequest
 *
 * @package Pff\ShengPay
 */
class PayRpcRequest extends RpcRequest
{
    // protected $sandbox = false;// 无沙箱环境

    /**
     * 创建支付订单
     *
     * @param array $order <p>
     *   参数:
     *    merchantOrderNo   String(32)  N   商户系统内的唯一订单号，商户订单号不能重复
     *    amount      String  N   该笔订单的交易金额，单位默认为RMB-元，精确到小数点后两位，如：23.42
     *    expireTime  String  Y   商户提交订单申请支付过期的时间,必须为14位正整数数字，且在商户系统保证唯一(yyyyMMddHHmmss)
     *    notifyUrl   String  N   盛付通支付平台在用户支付成功后通知商户服务端的地址，如：http://test.shengpay.com/mas/notify.php
     *    productName   String  N   商品名称(不超过32个字符)
     *    currency  String  N   货币类型，见附录3币种枚举定义：Currency CNY:人民币
     *    userIp    String  N   用户IP（用户下单时的IP-公网IP,银联交易必填）
     *    payChannel    String  N   支付渠道，见附录
     *    openid    String  Y   公众号，服务窗支付，银联支付码，微信条码，支付宝条码，微信小程序必填
     *    pageUrl   String  N   同步跳转URL（微信H5，支付宝H5，公众号，服务窗必传,同时这个地址的一级域名必须和报备的授权域名一致）
     *    exts  JSONString  Y   扩展属性,JSON串（微信H5 、微信小程序、微信APP必传，请参考备注）
     * </p>
     * @return PayRpcRequest
     * @throws ClientException
     */
    public function order($order)
    {
        ShengPayFilter::productName($order['productName']);

        if (! isset($order['userIp'])) {
            $this->options['query']['userIp']   = $this->getClientIp();
        }

        if (! isset($order['currency'])) {
            $this->options['query']['currency'] = $this->getCurrency();
        }

        $this->path('/web-acquire-channel/pay/order.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $order]);

        return $this;
    }

    /**
     * 单笔查询
     *
     * @param array $query
     * @return PayRpcRequest
     * @throws ClientException
     */
    public function query($query)
    {
//        ShengPayFilter::productName($query['productName']);

        $this->path('/web-acquire-channel/pay/query.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $query]);

        return $this;
    }

    /**
     * 退款
     *
     * @param $refund
     * @return $this
     * @throws ClientException
     */
    public function refund($refund)
    {
//        ShengPayFilter::productName($refund['productName']);

        $this->path('/web-acquire-channel/pay/refund.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $refund]);

        return $this;
    }

    /**
     * 退款查询
     *
     * @param $refundQuery
     * @return $this
     * @throws ClientException
     */
    public function refundQuery($refundQuery)
    {
//        ShengPayFilter::productName($refundQuery['productName']);

        $this->path('/web-acquire-channel/pay/refundQuery.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $refundQuery]);

        return $this;
    }

    /**
     * @deprecated see SharingRpcRequest::sharing()
     * 分账
     * @param $sharing
     * @return $this
     * @throws ClientException
     */
    public function sharing($sharing)
    {
//        ShengPayFilter::productName($sharing['productName']);

        $this->path('/web-acquire-channel/pay/sharing.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $sharing]);

        return $this;
    }

    /**
     * @deprecated see SharingRpcRequest::sharingQuery()
     * 分账查询
     * @param $sharingQuery
     * @return $this
     * @throws ClientException
     */
    public function sharingQuery($sharingQuery)
    {
//        ShengPayFilter::productName($sharingQuery['productName']);

        $this->path('/web-acquire-channel/pay/sharingQuery.htm');
        $this->product('shengpay')
            ->method('POST')
            ->options(['query' => $sharingQuery]);

        return $this;
    }
}
