<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

/**
 * Class CurrencyConverter
 * @package Varvaruk\PaymentsFeeCalculator\Service\Currency
 */
class CurrencyConverter implements CurrencyConverterInterface
{
    /**
     * CURRENCIES_WITHOUT_CENTS
     */
    public const CURRENCIES_WITHOUT_CENTS = ['JPY', 'KRW', 'IDR', 'CLP', 'VND', 'TWD', 'PYG', 'UGX'];
    /**
     * @var array
     */
    private array $exchangeRates;

    /**
     * CurrencyConverter constructor.
     * @param ExchangeRateProviderInterface $exchangeRateProvider
     */
    public function __construct(ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->setExchangeRates($exchangeRateProvider->getExchangeRates());
    }

    /**
     * @param array $exchangeRates
     */
    public function setExchangeRates(array $exchangeRates): void
    {
        foreach ($exchangeRates['rates'] as $currency => $rate) {
            $this->exchangeRates[$currency][$exchangeRates['base']] = 1 / $rate;
            $this->exchangeRates[$exchangeRates['base']][$currency] = $rate;
        }
    }

    /**
     * @param float $amount
     * @param string $sourceCurrency
     * @param string $targetCurrency
     * @return string|int|float
     */
    public function convert(float $amount, string $sourceCurrency, string $targetCurrency): string|int|float
    {
        if ($sourceCurrency === $targetCurrency) {
            return $amount;
        }

        if (isset($this->exchangeRates[$sourceCurrency][$targetCurrency])) {
            $rate = $this->exchangeRates[$sourceCurrency][$targetCurrency];
            return $amount * $rate;
        }

        throw new \InvalidArgumentException("Exchange rate for $sourceCurrency to $targetCurrency not found.");
    }
}
