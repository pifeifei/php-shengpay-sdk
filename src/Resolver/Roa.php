<?php

namespace Pff\Client\Resolver;

use Pff\Client\AlibabaCloud;
use Pff\Client\Exception\ClientException;
use Pff\Client\Request\RoaRequest;
use ReflectionClass;
use ReflectionException;

/**
 * Class Roa
 *
 * @codeCoverageIgnore
 * @package Pff\Client\Resolver
 */
abstract class Roa extends RoaRequest
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
        return $this->pathParameters;
    }
}
