<?php

namespace Pff\Client\Credentials;

use Pff\Client\Filter\CredentialFilter;
use Pff\Client\Exception\ClientException;

/**
 * Class BearerTokenCredential
 *
 * @package Pff\Client\Credentials
 */
class BearerTokenCredential implements CredentialsInterface
{

    /**
     * @var string
     */
    private $bearerToken;

    /**
     * Class constructor.
     *
     * @param string $bearerToken
     *
     * @throws ClientException
     */
    public function __construct($bearerToken)
    {
        CredentialFilter::bearerToken($bearerToken);

        $this->bearerToken = $bearerToken;
    }

    /**
     * @return string
     */
    public function getBearerToken()
    {
        return $this->bearerToken;
    }

    /**
     * @return string
     */
    public function getAccessKeyId()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getAccessKeySecret()
    {
        return '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "bearerToken#$this->bearerToken";
    }
}
