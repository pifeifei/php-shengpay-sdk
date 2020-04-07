<?php

namespace Pff\Client\Exception;

use Exception;
use RuntimeException;

/**
 * Class ClientException
 *
 * @package Pff\Client\Exception
 */
class ClientException extends PffException
{

    /**
     * ClientException constructor.
     *
     * @param string         $errorMessage
     * @param string         $errorCode
     * @param Exception|null $previous
     */
    public function __construct($errorMessage, $errorCode, $previous = null)
    {
        parent::__construct($errorMessage, 0, $previous);
        $this->errorMessage = $errorMessage;
        $this->errorCode    = $errorCode;
    }

    /**
     * @codeCoverageIgnore
     * @deprecated
     */
    public function getErrorType()
    {
        return 'Client';
    }
}
