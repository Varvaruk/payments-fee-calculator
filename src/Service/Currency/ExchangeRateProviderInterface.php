<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Currency;

interface ExchangeRateProviderInterface
{
    public function getExchangeRates(): array;
}
