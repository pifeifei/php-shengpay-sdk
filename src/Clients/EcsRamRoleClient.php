<?php

namespace Pff\Client\Clients;

use Pff\Client\Exception\ClientException;
use Pff\Client\Signature\ShaHmac1Signature;
use Pff\Client\Credentials\EcsRamRoleCredential;

/**
 * Use the RAM role of an ECS instance to complete the authentication.
 */
class EcsRamRoleClient extends Client
{
    /**
     * @param string $roleName
     *
     * @throws ClientException
     */
    public function __construct($roleName)
    {
        parent::__construct(
            new EcsRamRoleCredential($roleName),
            new ShaHmac1Signature()
        );
    }
}
