<?php

namespace Pff\Client\Clients;

use Pff\Client\AlibabaCloud;
use Pff\Client\Filter\Filter;
use Pff\Client\Request\Request;
use Pff\Client\Credentials\StsCredential;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Credentials\CredentialsInterface;
use Pff\Client\Credentials\EcsRamRoleCredential;
use Pff\Client\Credentials\RamRoleArnCredential;
use Pff\Client\Credentials\RsaKeyPairCredential;
use Pff\Client\Credentials\Providers\EcsRamRoleProvider;
use Pff\Client\Credentials\Providers\RamRoleArnProvider;
use Pff\Client\Credentials\Providers\RsaKeyPairProvider;
use Pff\Client\Credentials\Providers\CredentialsProvider;

/**
 * Trait ManageTrait.
 *
 * @mixin     Client
 */
trait ManageTrait
{
    /**
     * @param int $timeout
     * @param int $connectTimeout
     *
     * @return CredentialsInterface|StsCredential
     *
     * @throws ClientException
     * @throws ServerException
     */
    public function getSessionCredential($timeout = Request::TIMEOUT, $connectTimeout = Request::CONNECT_TIMEOUT)
    {
        switch (\get_class($this->credential)) {
            case EcsRamRoleCredential::class:
                return (new EcsRamRoleProvider($this))->get();
            case RamRoleArnCredential::class:
                return (new RamRoleArnProvider($this))->get($timeout, $connectTimeout);
            case RsaKeyPairCredential::class:
                return (new RsaKeyPairProvider($this))->get($timeout, $connectTimeout);
            default:
                return $this->credential;
        }
    }

    /**
     * @return static
     * @throws ClientException
     * @deprecated
     * @codeCoverageIgnore
     */
    public function asGlobalClient()
    {
        return $this->asDefaultClient();
    }

    /**
     * Set the current client as the default client.
     *
     * @return static
     * @throws ClientException
     */
    public function asDefaultClient()
    {
        return $this->name(CredentialsProvider::getDefaultName());
    }

    /**
     * Naming clients.
     *
     * @param string $name
     *
     * @return static
     * @throws ClientException
     */
    public function name($name)
    {
        Filter::name($name);

        return AlibabaCloud::set($name, $this);
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        if (isset($this->options['debug'])) {
            return $this->options['debug'] === true && PHP_SAPI === 'cli';
        }

        return false;
    }
}
