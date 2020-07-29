<?php

namespace Pff\Client\Signature;

/**
 * Class MD5Signature
 *
 * @package   AlibabaCloud\Signature
 */
class MD5Signature extends Signature implements SignatureInterface
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
    public function sign($string, $accessKeySecret = '')
    {
        return strtoupper(md5($string . $accessKeySecret));
    }
}
