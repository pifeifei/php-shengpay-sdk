<?php

namespace Pff\ShengPay\Filter;

use Pff\Client\Exception\ClientException;
use Pff\Client\SDK;
use Stringy\StaticStringy;

class ShengPayFilter
{
    /**
     * @param $productName
     *
     * @return string
     *
     * @throws ClientException
     */
    public static function productName($productName)
    {
        if (!is_string($productName)) {
            throw new ClientException(
                'ProductName must be a string',
                SDK::INVALID_ARGUMENT
            );
        }

        if ($productName === '') {
            throw new ClientException(
                'ProductName cannot be empty',
                SDK::INVALID_ARGUMENT
            );
        }

        if (StaticStringy::length($productName) > 32) {
            throw new ClientException(
                'ProductName string length must be less than 32.',
                SDK::INVALID_ARGUMENT
            );
        }

        return $productName;
    }
}
