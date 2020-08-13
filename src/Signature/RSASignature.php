<?php

namespace Pff\Client\Signature;

/**
 * Class ShaHmac1Signature
 *
 * @package   AlibabaCloud\Signature
 */
class RSASignature extends Signature implements SignatureInterface
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return 'RSA';
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
        if (empty($string))
        {
            return false;
        }
        if (file_exists($accessKeySecret)){
            $private_key = file_get_contents($accessKeySecret);
        } else {
            $private_key = $accessKeySecret;
        }

        if (empty($private_key))
        {
            return false;
        }

        $pKeyId = openssl_get_privatekey($private_key);
        if (empty($pKeyId))
        {
            return false;
        }
        $verify = openssl_sign($string, $signature, $pKeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($pKeyId);
        return $verify ? base64_encode($signature) : false;
    }
}
