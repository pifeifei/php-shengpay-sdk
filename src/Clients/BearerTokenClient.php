<?php

namespace Pff\Client\Clients;

use Pff\Client\Exception\ClientException;
use Pff\Client\Signature\BearerTokenSignature;
use Pff\Client\Credentials\BearerTokenCredential;

/**
 * Use the Bearer Token to complete the authentication.
 *
 * @package Pff\Client\Clients
 */
class BearerTokenClient extends Client
{

    /**
     * @param string $bearerToken
     *
     * @throws ClientException
     */
    public function __construct($bearerToken)
    {
        parent::__construct(
            new BearerTokenCredential($bearerToken),
            new BearerTokenSignature()
        );
    }
}
