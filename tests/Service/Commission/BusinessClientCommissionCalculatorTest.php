<?php
namespace Service\Commission;

use PHPUnit\Framework\TestCase;
use Varvaruk\PaymentsFeeCalculator\Service\Commission\BusinessClientCommissionCalculator;

class BusinessClientCommissionCalculatorTest extends TestCase
{
    public function testCalculateWithdrawCommission()
    {
        $commissionCalculator = BusinessClientCommissionCalculator::getInstance();

        // Test for operation with 0 commission
        $operationDate = '2023-08-07';
        $userId = 1;
        $operationAmount = 1000.0;
        $operationCurrency = 'EUR';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(5.00, $commission);

        // Test for operation with commission
        $operationDate = '2023-08-08';
        $userId = 1;
        $operationAmount = 500.0;
        $operationCurrency = 'EUR';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(2.5, $commission);

        // Test for operation with different currency
        $operationDate = '2023-08-10';
        $userId = 1;
        $operationAmount = 500.0;
        $operationCurrency = 'USD';
        $commission = $commissionCalculator->calculateWithdrawCommission($operationDate, $userId, $operationAmount, $operationCurrency);
        $this->assertEquals(2.5, $commission);
    }
}

