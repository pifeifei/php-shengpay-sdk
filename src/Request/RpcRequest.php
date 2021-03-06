<?php

namespace Pff\Client\Request;

use Exception;
use RuntimeException;
use Pff\Client\Support\Sign;
use Pff\Client\Support\Arrays;
use Pff\Client\Credentials\StsCredential;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;
use Pff\Client\Credentials\BearerTokenCredential;

/**
 * RESTful RPC Request.
 *
 * @package Pff\Client\Request
 */
class RpcRequest extends Request
{

    /**
     * @var string
     */
    protected $dateTimeFormat = 'Y-m-d\TH:i:s\Z';

    /**
     * Resolve request parameter.
     *
     * @throws ClientException
     */
    public function resolveParameter()
    {
        $this->resolveBoolInParameters();
        $this->resolveCommonParameters();
        $this->repositionParameters();
    }

    /**
     * Convert a Boolean value to a string
     */
    private function resolveBoolInParameters()
    {
        if (isset($this->options['query'])) {
            $this->options['query'] = array_map(
                static function ($value) {
                    return self::boolToString($value);
                },
                $this->options['query']
            );
        }
    }

    /**
     * Convert a Boolean value to a string.
     *
     * @param bool|string $value
     *
     * @return string
     */
    public static function boolToString($value)
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }

    /**
     * Resolve Common Parameters.
     *
     * @throws ClientException
     * @throws Exception
     */
    protected function resolveCommonParameters()
    {
        $signature                                  = $this->httpClient()->getSignature();
//        $this->options['query']['RegionId']         = $this->realRegionId();
        $this->options['query']['Format']           = $this->format;
        $this->options['query']['SignatureMethod']  = $signature->getMethod();
        $this->options['query']['SignatureVersion'] = $signature->getVersion();
        $this->options['query']['SignatureNonce']   = Sign::uuid($this->product . $this->realRegionId());
        $this->options['query']['Timestamp']        = gmdate($this->dateTimeFormat);
        $this->options['query']['Action']           = $this->action;
        if ($this->credential()->getAccessKeyId()) {
            $this->options['query']['AccessKeyId'] = $this->credential()->getAccessKeyId();
        }
        if ($signature->getType()) {
            $this->options['query']['SignatureType'] = $signature->getType();
        }
        if (!isset($this->options['query']['Version'])) {
            $this->options['query']['Version'] = $this->version;
        }
        $this->resolveSecurityToken();
        $this->resolveBearerToken();
        $this->options['query']['Signature'] = $this->signature();
    }

    /**
     * @throws ClientException
     * @throws ServerException
     */
    private function resolveSecurityToken()
    {
        if (!$this->credential() instanceof StsCredential) {
            return;
        }

        if (!$this->credential()->getSecurityToken()) {
            return;
        }

        $this->options['query']['SecurityToken'] = $this->credential()->getSecurityToken();
    }

    /**
     * @throws ClientException
     * @throws ServerException
     */
    private function resolveBearerToken()
    {
        if ($this->credential() instanceof BearerTokenCredential) {
            $this->options['query']['BearerToken'] = $this->credential()->getBearerToken();
        }
    }

    /**
     * Sign the parameters.
     *
     * @return mixed
     * @throws ClientException
     * @throws ServerException
     */
    protected function signature()
    {
        return $this->httpClient()
                    ->getSignature()
                    ->sign(
                        $this->stringToSign(),
                        $this->credential()->getAccessKeySecret()
                    );
    }

    /**
     * @return string
     */
    public function stringToSign()
    {
        $query       = isset($this->options['query']) ? $this->options['query'] : [];
        $form_params = isset($this->options['form_params']) ? $this->options['form_params'] : [];
        $json        = isset($this->options['json']) ? $this->options['json'] : [];
        $parameters  = Arrays::merge([$query, $form_params, $json]);

        return json_encode($parameters);
    }

    /**
     * Adjust parameter position
     */
    protected function repositionParameters()
    {
        if ($this->method === 'POST' || $this->method === 'PUT') {
            foreach ($this->options['query'] as $api_key => $api_value) {
                $this->options['form_params'][$api_key] = $api_value;
            }
            unset($this->options['query']);
        }
    }

    /**
     * Magic method for set or get request parameters.
     *
     * @param string $name
     * @param mixed  $arguments
     *
     * @return $this
     */
    public function __call($name, $arguments)
    {
        if (strncmp($name, 'get', 3) === 0) {
            $parameter_name = \mb_strcut($name, 3);

            return $this->__get($parameter_name);
        }

        if (strncmp($name, 'with', 4) === 0) {
            $parameter_name = \mb_strcut($name, 4);
            $this->__set($parameter_name, $arguments[0]);
            $this->options['query'][$parameter_name] = $arguments[0];

            return $this;
        }

        if (strncmp($name, 'set', 3) === 0) {
            $parameter_name = \mb_strcut($name, 3);
            $with_method    = "with$parameter_name";

            throw new RuntimeException("Please use $with_method instead of $name");
        }

        throw new RuntimeException('Call to undefined method ' . __CLASS__ . '::' . $name . '()');
    }
}
