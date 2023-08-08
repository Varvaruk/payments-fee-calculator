<?php

namespace Service\Currency;

use PHPUnit\Framework\TestCase;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverter;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\ExchangeRateProviderInterface;

class CurrencyConverterTest extends TestCase
{
    public function testConvert()
    {
        $mockExchangeRateProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $exchangeRates = [
            'base' => 'EUR',
            'date' => '2023-08-07',
            'rates' => [
                'AED' => 4.147043,
            ]
        ];
        $mockExchangeRateProvider->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn($exchangeRates);

        $converter = new CurrencyConverter($mockExchangeRateProvider);

        $amount = 100.0;
        $sourceCurrency = 'EUR';
        $targetCurrency = 'AED';
        $convertedAmount = $converter->convert($amount, $sourceCurrency, $targetCurrency);
        $this->assertEquals(414.7043, $convertedAmount);

        $amount = 414.7043;
        $sourceCurrency = 'AED';
        $targetCurrency = 'EUR';
        $convertedAmount = $converter->convert($amount, $sourceCurrency, $targetCurrency);
        $this->assertEquals(100.0, $convertedAmount);
    }
}
