<?php

namespace Service\Commission;

use PHPUnit\Framework\TestCase;
use Varvaruk\PaymentsFeeCalculator\Service\Commission\PrivateClientCommissionCalculator;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverter;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\ExchangeRateProviderInterface;

class PrivateClientCommissionCalculatorTest extends TestCase
{
    public function testCalculateWithdrawCommission()
    {
        // Mock the ExchangeRateProviderInterface and set the exchange rates for the currency converter
        $mockExchangeRateProvider = $this->createMock(ExchangeRateProviderInterface::class);
        $exchangeRates = [
            'base' => 'EUR',
            'date' => '2023-08-07',
            'rates' => [
                'USD' => 1.1497,
                'JPY' => 129.53,
                // Add more exchange rates as needed for your test cases
            ]
        ];
        $mockExchangeRateProvider->expects($this->once())
            ->method('getExchangeRates')
            ->willReturn($exchangeRates);

        $currencyConverter = new CurrencyConverter($mockExchangeRateProvider);

        // Create an instance of PrivateClientCommissionCalculator and pass the currency converter to it
        $commissionCalculator = new PrivateClientCommissionCalculator($currencyConverter);

        $operationDate = '2023-08-07';
        $userId = 1;
        $operationAmount = 800.0;
        $operationCurrency = 'EUR';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(0.00, $commission);

        $operationDate = '2023-08-08';
        $userId = 1;
        $operationAmount = 200.0;
        $operationCurrency = 'EUR';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(0.00, $commission);

        // Test for operation exceeding excess amount
        $operationDate = '2023-08-10';
        $userId = 1;
        $operationAmount = 500.0;
        $operationCurrency = 'EUR';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(1.50, $commission);

    }
}
