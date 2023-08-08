<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

/**
 * Interface ExchangeRateProviderInterface
 * @package Varvaruk\PaymentsFeeCalculator\Service\Currency
 */
interface ExchangeRateProviderInterface
{
    /**
     * @return array
     */
    public function getExchangeRates(): array;
}
