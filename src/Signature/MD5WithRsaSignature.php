<?php


namespace Pff\Client\Signature;

class MD5WithRsaSignature extends Signature implements SignatureInterface
{

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return 'RSA';
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function getVersion()
    {
        return '1.0';
    }

    /**
     * @inheritDoc
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
        $verify = openssl_sign($string, $signature, $pKeyId, OPENSSL_ALGO_MD5);
        openssl_free_key($pKeyId);
        return $verify ? base64_encode($signature) : false;
    }
}
