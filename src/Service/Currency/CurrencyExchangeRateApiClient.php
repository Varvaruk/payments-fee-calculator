<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

/**
 * Class CurrencyExchangeRateApiClient
 * @package Varvaruk\PaymentsFeeCalculator\Service\Currency
 */
class CurrencyExchangeRateApiClient implements ExchangeRateProviderInterface
{
    /**
     * @var string
     */
    private string $apiEndpoint;

    /**
     * @var ClientInterface
     */
    private ClientInterface $httpClient;

    /**
     * CurrencyExchangeRateApiClient constructor.
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->apiEndpoint = getenv('CURRENCY_API_ENDPOINT');
        $this->httpClient = $httpClient;
    }

    /**
     * @return array
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getExchangeRates(): array
    {
        $response = $this->httpClient->get($this->apiEndpoint);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Failed to fetch exchange rates.');
        }

        $data = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($data['rates'])) {
            throw new \RuntimeException('Invalid API response format.');
        }

        return $data;
    }
}
