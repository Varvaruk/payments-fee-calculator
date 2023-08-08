<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

use GuzzleHttp\ClientInterface;

class CurrencyExchangeRateApiClient implements ExchangeRateProviderInterface
{
    private $apiEndpoint;

    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->apiEndpoint = getenv('CURRENCY_API_ENDPOINT');
        $this->httpClient = $httpClient;
    }

    public function getExchangeRates(): array
    {
        $response = $this->httpClient->get($this->apiEndpoint);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch exchange rates.');
        }

        $data = json_decode($response->getBody(), true);

        if (!isset($data['rates'])) {
            throw new \RuntimeException('Invalid API response format.');
        }

        return $data;
    }
}
