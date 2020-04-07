<?php

namespace Pff\Client\Credentials\Requests;

use Pff\Client\Request\RpcRequest;
use Pff\Client\Exception\ClientException;
use Pff\Client\Credentials\Providers\Provider;
use Pff\Client\Credentials\RamRoleArnCredential;

/**
 * Retrieving assume role credentials.
 *
 * @package Pff\Client\Credentials\Requests
 */
class AssumeRole extends RpcRequest
{

    /**
     * AssumeRole constructor.
     *
     * @param RamRoleArnCredential $arnCredential
     *
     * @throws ClientException
     */
    public function __construct(RamRoleArnCredential $arnCredential)
    {
        parent::__construct();
        $this->product('Sts');
        $this->version('2015-04-01');
        $this->action('AssumeRole');
        $this->host('sts.aliyuncs.com');
        $this->scheme('https');
        $this->regionId('cn-hangzhou');
        $this->options['verify']                   = false;
        $this->options['query']['RoleArn']         = $arnCredential->getRoleArn();
        $this->options['query']['RoleSessionName'] = $arnCredential->getRoleSessionName();
        $this->options['query']['DurationSeconds'] = Provider::DURATION_SECONDS;
        if ($arnCredential->getPolicy()) {
            if (is_array($arnCredential->getPolicy())) {
                $this->options['query']['Policy'] = json_encode($arnCredential->getPolicy());
            }
            if (is_string($arnCredential->getPolicy())) {
                $this->options['query']['Policy'] = $arnCredential->getPolicy();
            }
        }
    }
}
