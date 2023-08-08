<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

interface CurrencyConverterInterface
{
    /**
     * Set the exchange rates for currency conversion.
     *
     * @param array $exchangeRates An associative array of exchange rates,
     *                            where keys are source currencies and values are arrays
     *                            of target currencies with their rates.
     *                            Example: ['base' => 'EUR','date' => '2023-08-07','rates' => ['AED' => 4.147043,]
     */
    public function setExchangeRates(array $exchangeRates): void;

    /**
     * Convert the amount from one currency to another.
     *
     * @param float $amount The amount to convert.
     * @param string $sourceCurrency The source currency code.
     * @param string $targetCurrency The target currency code.
     *
     * @return string|int|float The converted amount.
     */
    public function convert(float $amount, string $sourceCurrency, string $targetCurrency): string|int|float;
}
