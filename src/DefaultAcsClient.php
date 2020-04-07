<?php

namespace Pff\Client;

use Pff\Client\Result\Result;
use Pff\Client\Clients\Client;
use Pff\Client\Request\Request;
use Pff\Client\Exception\ClientException;
use Pff\Client\Exception\ServerException;

/**
 * Class DefaultAcsClient
 *
 * @package    AlibabaCloud
 *
 * @deprecated deprecated since version 2.0, Use AlibabaCloud instead.
 * @codeCoverageIgnore
 */
class DefaultAcsClient
{

    /**
     * @var string
     */
    public $randClientName;

    /**
     * DefaultAcsClient constructor.
     *
     * @param Client $client
     *
     * @throws ClientException
     */
    public function __construct(Client $client)
    {
        $this->randClientName = \uniqid('', true);
        $client->name($this->randClientName);
    }

    /**
     * @param Request|Result $request
     *
     * @return Result|string
     * @throws ClientException
     * @throws ServerException
     */
    public function getAcsResponse($request)
    {
        if ($request instanceof Result) {
            return $request;
        }

        return $request->client($this->randClientName)->request();
    }
}
