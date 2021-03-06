<?php

namespace Pff\Client\Resolver;

use Pff\Client\Exception\ClientException;

/**
 * Class VersionResolver
 *
 * @codeCoverageIgnore
 * @package Pff\Client\Resolver
 */
abstract class VersionResolver
{

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static())->__call($name, $arguments);
    }

    /**
     * @param string $version
     * @param array  $arguments
     *
     * @return mixed
     * @throws ClientException
     */
    public function __call($version, $arguments)
    {
        $version = \ucfirst($version);
        $product = $this->getProductName();

        $position = strpos($product, 'Version');
        if ($position !== false && $position !== 0) {
            $product = \str_replace('Version', '', $product);
        }

        $class = "AlibabaCloud\\{$product}\\$version\\{$product}ApiResolver";

        if (\class_exists($class)) {
            return new $class();
        }

        throw new ClientException(
            "$product Versions contains no {$version}",
            'SDK.VersionNotFound'
        );
    }

    /**
     * @return mixed
     * @throws ClientException
     */
    private function getProductName()
    {
        $array = \explode('\\', \get_class($this));

        if (isset($array[1])) {
            return $array[1];
        }

        throw new ClientException(
            'Service name not found.',
            'SDK.ServiceNotFound'
        );
    }
}
