<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

class CurrencyConverter implements CurrencyConverterInterface
{
    public const CURRENCIES_WITHOUT_CENTS = ['JPY', 'KRW', 'IDR', 'CLP', 'VND', 'TWD', 'PYG', 'UGX'];

    private array $exchangeRates;

    public function __construct(ExchangeRateProviderInterface $exchangeRateProvider)
    {
        $this->setExchangeRates($exchangeRateProvider->getExchangeRates());
    }

    public function setExchangeRates(array $exchangeRates): void
    {
        foreach ($exchangeRates['rates'] as $currency => $rate) {
            $this->exchangeRates[$currency][$exchangeRates['base']] = 1 / $rate;
            $this->exchangeRates[$exchangeRates['base']][$currency] = $rate;
        }
    }

    public function convert(float $amount, string $sourceCurrency, string $targetCurrency): string|int|float
    {
        if ($sourceCurrency === $targetCurrency) {
            return $amount; // No conversion needed, same currency
        }

        if (isset($this->exchangeRates[$sourceCurrency][$targetCurrency])) {
            $rate = $this->exchangeRates[$sourceCurrency][$targetCurrency];
            return $amount * $rate;
        }

        throw new \InvalidArgumentException("Exchange rate for $sourceCurrency to $targetCurrency not found.");
    }
}
