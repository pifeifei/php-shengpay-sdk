<?php

namespace Pff\Client\Credentials;

use Exception;
use Pff\Client\SDK;
use Pff\Client\Filter\CredentialFilter;
use Pff\Client\Exception\ClientException;

/**
 * Use the RSA key pair to complete the authentication (supported only on Japanese site)
 *
 * @package Pff\Client\Credentials
 */
class RsaKeyPairCredential implements CredentialsInterface
{

    /**
     * @var string
     */
    private $publicKeyId;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * RsaKeyPairCredential constructor.
     *
     * @param string $publicKeyId
     * @param string $privateKeyFile
     *
     * @throws ClientException
     */
    public function __construct($publicKeyId, $privateKeyFile)
    {
        CredentialFilter::publicKeyId($publicKeyId);
        CredentialFilter::privateKeyFile($privateKeyFile);

        $this->publicKeyId = $publicKeyId;
        try {
            $this->privateKey = file_get_contents($privateKeyFile);
        } catch (Exception $exception) {
            throw new ClientException(
                $exception->getMessage(),
                SDK::INVALID_CREDENTIAL
            );
        }
    }

    /**
     * @return mixed
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getPublicKeyId()
    {
        return $this->publicKeyId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "publicKeyId#$this->publicKeyId";
    }
}
