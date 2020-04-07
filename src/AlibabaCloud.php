<?php

namespace Pff\Client;

use Pff\Client\Traits\LogTrait;
use Pff\Client\Traits\MockTrait;
use Pff\Client\Traits\ClientTrait;
use Pff\Client\Traits\HistoryTrait;
use Pff\Client\Traits\RequestTrait;
use Pff\Client\Traits\EndpointTrait;
use Pff\Client\Traits\DefaultRegionTrait;
use Pff\Client\Exception\ClientException;

/**
 * Class AlibabaCloud
 *
 * @package Pff\Client
 * @ mixin     \Pff\IdeHelper
 */
class AlibabaCloud
{
    use ClientTrait;
    use DefaultRegionTrait;
    use EndpointTrait;
    use RequestTrait;
    use MockTrait;
    use HistoryTrait;
    use LogTrait;

    /**
     * Version of the Client
     */
    const VERSION = '1.5.20';

    /**
     * This static method can directly call the specific service.
     *
     * @param string $product
     * @param array  $arguments
     *
     * @codeCoverageIgnore
     * @return object
     * @throws ClientException
     */
    public static function __callStatic($product, $arguments)
    {
        $product = \ucfirst($product);
        // TODO: AlibabaCloud -> Pff 是否正确
        $product_class = 'Pff' . '\\' . $product . '\\' . $product;

        if (\class_exists($product_class)) {
            return new $product_class;
        }

        throw new ClientException(
            "May not yet support product $product quick access, "
            . 'you can use [Alibaba Cloud Client for PHP] to send any custom '
            . 'requests: https://github.com/aliyun/openapi-sdk-php-client/blob/master/docs/en-US/3-Request.md',
            SDK::SERVICE_NOT_FOUND
        );
    }
}
