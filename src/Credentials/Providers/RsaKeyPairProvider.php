<?php

namespace Pff\Client\Credentials\Providers;

use Pff\Client\SDK;
use Pff\Client\AlibabaCloud;
use Pff\Client\Result\Result;
use Pff\Client\Request\Request;
use Pff\Client\Credentials\StsCredential;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Credentials\AccessKeyCredential;
use Pff\Client\Signature\ShaHmac256WithRsaSignature;
use Pff\Client\Credentials\Requests\GenerateSessionAccessKey;

/**
 * Class RsaKeyPairProvider
 *
 * @package Pff\Client\Credentials\Providers
 */
class RsaKeyPairProvider extends Provider
{

    /**
     * Get credential.
     *
     * @param int $timeout
     * @param int $connectTimeout
     *
     * @return StsCredential
     * @throws ClientException
     * @throws ServerException
     */
    public function get($timeout = Request::TIMEOUT, $connectTimeout = Request::CONNECT_TIMEOUT)
    {
        $credential = $this->getCredentialsInCache();

        if ($credential === null) {
            $result = $this->request($timeout, $connectTimeout);

            if (!isset($result['SessionAccessKey']['SessionAccessKeyId'],
                $result['SessionAccessKey']['SessionAccessKeySecret'])) {
                throw new ServerException($result, $this->error, SDK::INVALID_CREDENTIAL);
            }

            $credential = $result['SessionAccessKey'];
            $this->cache($credential);
        }

        return new StsCredential(
            $credential['SessionAccessKeyId'],
            $credential['SessionAccessKeySecret']
        );
    }

    /**
     * Get credentials by request.
     *
     * @param $timeout
     * @param $connectTimeout
     *
     * @return Result
     * @throws ClientException
     * @throws ServerException
     */
    private function request($timeout, $connectTimeout)
    {
        $clientName = __CLASS__ . \uniqid('rsa', true);
        $credential = $this->client->getCredential();

        AlibabaCloud::client(
            new AccessKeyCredential(
                $credential->getPublicKeyId(),
                $credential->getPrivateKey()
            ),
            new ShaHmac256WithRsaSignature()
        )->name($clientName);

        return (new GenerateSessionAccessKey($credential->getPublicKeyId()))
            ->client($clientName)
            ->timeout($timeout)
            ->connectTimeout($connectTimeout)
            ->debug($this->client->isDebug())
            ->request();
    }
}
