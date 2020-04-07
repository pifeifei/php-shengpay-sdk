<?php

namespace Pff\ShengPay\Signature;

use Pff\Client\Signature\Signature;
use Pff\Client\Signature\SignatureInterface;

/**
 * Class ShengPaySignature
 *
 * @package   Pff\Signature
 */
class ShengPaySignature extends Signature implements SignatureInterface
{

    /**
     * @return string
     */
    public function getMethod()
    {
        return 'MD5';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * @param string $string
     * @param string $accessKeySecret
     *
     * @return string
     */
    public function sign($string, $accessKeySecret)
    {
        return strtoupper(hash_hmac('md5', $string . $accessKeySecret, ''));
    }
}
