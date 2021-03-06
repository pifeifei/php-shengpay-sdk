<?php

namespace Pff\Client\Credentials;

use Pff\Client\Filter\CredentialFilter;
use Pff\Client\Exception\ClientException;

/**
 * Use the AccessKey to complete the authentication.
 *
 * @package Pff\Client\Credentials
 */
class AccessKeyCredential implements CredentialsInterface
{

    /**
     * @var string
     */
    private $accessKeyId;

    /**
     * @var string
     */
    private $accessKeySecret;

    /**
     * AccessKeyCredential constructor.
     *
     * @param string $accessKeyId     Access key ID
     * @param string $accessKeySecret Access Key Secret
     *
     * @throws ClientException
     */
    public function __construct($accessKeyId, $accessKeySecret)
    {
        CredentialFilter::AccessKey($accessKeyId, $accessKeySecret);

        $this->accessKeyId     = $accessKeyId;
        $this->accessKeySecret = $accessKeySecret;
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return $this->accessKeyId;
    }

    /**
     * @return string
     */
    public function getAccessKeySecret()
    {
        return $this->accessKeySecret;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "$this->accessKeyId#$this->accessKeySecret";
    }
}
