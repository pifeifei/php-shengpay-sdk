<?php

namespace Pff\Client\Resolver;

use ReflectionClass;
use ReflectionException;
use Pff\Client\AlibabaCloud;
use Pff\Client\Request\Request;
use Pff\Client\Exception\ClientException;

/**
 * @codeCoverageIgnore
 * @mixin Rpc
 * @mixin Roa
 * @mixin Request
 * @package Pff\Client\Resolver
 */
trait ActionResolverTrait
{

    /**
     * Resolve Action name from class name
     */
    private function resolveActionName()
    {
        if (!$this->action) {
            $array        = explode('\\', get_class($this));
            $this->action = array_pop($array);
        }
    }

    /**
     * Append SDK version into User-Agent
     *
     * @throws ClientException
     * @throws ReflectionException
     */
    private function appendSdkUA()
    {
        if (!(new ReflectionClass(AlibabaCloud::class))->hasMethod('appendUserAgent')) {
            return;
        }

        if (!class_exists('AlibabaCloud\Release')) {
            return;
        }

        AlibabaCloud::appendUserAgent('SDK', \AlibabaCloud\Release::VERSION);
    }
}
