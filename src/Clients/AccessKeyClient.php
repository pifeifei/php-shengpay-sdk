<?php

namespace Pff\Client\Clients;

use Pff\Client\Exception\ClientException;
use Pff\Client\Signature\ShaHmac1Signature;
use Pff\Client\Credentials\AccessKeyCredential;

/**
 * Use the AccessKey to complete the authentication.
 *
 * @package Pff\Client\Clients
 */
class AccessKeyClient extends Client
{

    /**
     * @param string $accessKeyId
     * @param string $accessKeySecret
     *
     * @throws ClientException
     */
    public function __construct($accessKeyId, $accessKeySecret)
    {
        parent::__construct(
            new AccessKeyCredential($accessKeyId, $accessKeySecret),
            new ShaHmac1Signature()
        );
    }
}
