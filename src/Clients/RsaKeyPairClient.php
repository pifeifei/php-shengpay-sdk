<?php

namespace Pff\Client\Clients;

use Pff\Client\Exception\ClientException;
use Pff\Client\Signature\ShaHmac1Signature;
use Pff\Client\Credentials\RsaKeyPairCredential;

/**
 * Use the RSA key pair to complete the authentication (supported only on Japanese site)
 *
 * @package Pff\Client\Clients
 */
class RsaKeyPairClient extends Client
{

    /**
     * @param string $publicKeyId
     * @param string $privateKeyFile
     *
     * @throws ClientException
     */
    public function __construct($publicKeyId, $privateKeyFile)
    {
        parent::__construct(
            new RsaKeyPairCredential($publicKeyId, $privateKeyFile),
            new ShaHmac1Signature()
        );
    }
}
