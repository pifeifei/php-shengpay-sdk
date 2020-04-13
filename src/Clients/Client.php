<?php

namespace Pff\Client\Clients;

use Pff\Client\Request\Request;
use Pff\Client\Signature\MD5Signature;
use Pff\Client\Traits\HttpTrait;
use Pff\Client\Traits\RegionTrait;
use Pff\Client\Credentials\StsCredential;
use Pff\Client\Signature\ShaHmac1Signature;
use Pff\Client\Signature\SignatureInterface;
use Pff\Client\Signature\ShaHmac256Signature;
use Pff\Client\Signature\BearerTokenSignature;
use Pff\Client\Credentials\AccessKeyCredential;
use Pff\Client\Credentials\CredentialsInterface;
use Pff\Client\Credentials\EcsRamRoleCredential;
use Pff\Client\Credentials\RamRoleArnCredential;
use Pff\Client\Credentials\RsaKeyPairCredential;
use Pff\Client\Credentials\BearerTokenCredential;
use Pff\Client\Signature\ShaHmac256WithRsaSignature;

/**
 * Custom Client.
 *
 * @package Pff\Client\Clients
 */
class Client
{
    use HttpTrait;
    use RegionTrait;
    use ManageTrait;

    /**
     * @var CredentialsInterface|AccessKeyCredential|BearerTokenCredential|StsCredential|EcsRamRoleCredential|RamRoleArnCredential|RsaKeyPairCredential
     */
    private $credential;

    /**
     * @var SignatureInterface
     */
    private $signature;

    /**
     * Self constructor.
     *
     * @param CredentialsInterface $credential
     * @param SignatureInterface   $signature
     */
    public function __construct(CredentialsInterface $credential, SignatureInterface $signature)
    {
        $this->credential                 = $credential;
        $this->signature                  = $signature;
        $this->options['connect_timeout'] = Request::CONNECT_TIMEOUT;
        $this->options['timeout']         = Request::TIMEOUT;
        $this->options['verify']          = false;
    }

    /**
     * @return AccessKeyCredential|BearerTokenCredential|CredentialsInterface|EcsRamRoleCredential|RamRoleArnCredential|RsaKeyPairCredential|StsCredential
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * @param  SignatureInterface $signature
     * @return $this
     */
    public function setSignature(SignatureInterface $signature)
    {
        $this->signature = $signature;
        return $this;
    }
    /**
     * @return SignatureInterface|BearerTokenSignature|ShaHmac1Signature|ShaHmac256Signature|ShaHmac256WithRsaSignature|MD5Signature
     */
    public function getSignature()
    {
        return $this->signature;
    }
}
