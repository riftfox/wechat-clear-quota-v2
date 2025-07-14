<?php

namespace Riftfox\Wechat\ClearQuota\V2;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Riftfox\Wechat\Application\ApplicationInterface;
use Riftfox\Wechat\Exception\ExceptionFactoryInterface;

class ClearQuotaV2Provider implements ClearQuotaV2ProviderInterface
{
    private ClientInterface $client;
    private RequestFactoryInterface $requestFactory;
    private UriFactoryInterface $uriFactory;
    private StreamFactoryInterface $streamFactory;
    private ExceptionFactoryInterface $exceptionFactory;
    public function __construct(ClientInterface $client, RequestFactoryInterface $requestFactory, UriFactoryInterface $uriFactory, StreamFactoryInterface $streamFactory, ExceptionFactoryInterface $exceptionFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->uriFactory = $uriFactory;
        $this->streamFactory = $streamFactory;
        $this->exceptionFactory = $exceptionFactory;
    }

    public function clearQuota(ApplicationInterface $application): void
    {
        $request = $this->getRequest($application);
        $response = $this->client->sendRequest($request);
        $data = json_decode($response->getBody()->getContents(),true);

        if($data['errcode'] != 0)
        {
            throw $this->exceptionFactory->createException($data['errcode'],$data['errmsg']);
        }
    }

    public function getRequest(ApplicationInterface $application):RequestInterface
    {
        $uri = $this->uriFactory->createUri(self::CLEAR_QUOTA_V2_URL);
        $request = $this->requestFactory->createRequest('POST', $uri);
        $body = json_encode([
            'appid' => $application->getAppId(),
            'appsecret' => $application->getAppSecret()
        ]);
        return $request->withBody($this->streamFactory->createStream($body));
    }
}