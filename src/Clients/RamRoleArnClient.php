<?php

namespace Pff\Client\Clients;

use Pff\Client\Exception\ClientException;
use Pff\Client\Signature\ShaHmac1Signature;
use Pff\Client\Credentials\RamRoleArnCredential;

/**
 * Use the AssumeRole of the RAM account to complete  the authentication.
 *
 * @package Pff\Client\Clients
 */
class RamRoleArnClient extends Client
{

    /**
     * @param string       $accessKeyId
     * @param string       $accessKeySecret
     * @param string       $roleArn
     * @param string       $roleSessionName
     * @param string|array $policy
     *
     * @throws ClientException
     */
    public function __construct($accessKeyId, $accessKeySecret, $roleArn, $roleSessionName, $policy = '')
    {
        parent::__construct(
            new RamRoleArnCredential($accessKeyId, $accessKeySecret, $roleArn, $roleSessionName, $policy),
            new ShaHmac1Signature()
        );
    }
}
