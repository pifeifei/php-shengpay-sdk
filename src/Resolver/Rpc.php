<?php

namespace Pff\Client\Resolver;

use Pff\Client\Exception\ClientException;
use Pff\Client\Request\RpcRequest;
use ReflectionException;

/**
 * Class Rpc
 *
 * @codeCoverageIgnore
 * @package Pff\Client\Resolver
 */
abstract class Rpc extends RpcRequest
{
    use ActionResolverTrait;
    use CallTrait;

    /**
     * @param array $options
     *
     * @throws ReflectionException
     * @throws ClientException
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $this->resolveActionName();
        $this->appendSdkUA();
    }

    /**
     * @return mixed
     */
    private function &parameterPosition()
    {
        return $this->options['query'];
    }
}
