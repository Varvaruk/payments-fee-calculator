<?php

namespace Service;

use PHPUnit\Framework\TestCase;
use Varvaruk\PaymentsFeeCalculator\Service\CSVProcessor;
use Varvaruk\PaymentsFeeCalculator\Service\Commission\CommissionCalculatorFactory;
use Varvaruk\PaymentsFeeCalculator\Service\Commission\CommissionCalculatorInterface;

class CSVProcessorTest extends TestCase
{
    public function testProcessCSV()
    {
        $calculatorFactoryMock = $this->createMock(CommissionCalculatorFactory::class);

        $commissionCalculatorMock1 = $this->createMock(CommissionCalculatorInterface::class);
        $commissionCalculatorMock1->method('calculateCommission')->willReturn(5.0);

        $commissionCalculatorMock2 = $this->createMock(CommissionCalculatorInterface::class);
        $commissionCalculatorMock2->method('calculateCommission')->willReturn(10.0);

        $calculatorFactoryMock->expects($this->exactly(2))
            ->method('getCalculator')
            ->withConsecutive(['test_user_type'], ['another_user_type'])
            ->willReturnOnConsecutiveCalls($commissionCalculatorMock1, $commissionCalculatorMock2);

        $csvProcessor = new CSVProcessor($calculatorFactoryMock);

        $csvFilePath = __DIR__ . '/../fixtures/data.csv';

        $result = $csvProcessor->processCSV($csvFilePath);

        $expectedResult = [5.0, 10.0];
        $this->assertEquals($expectedResult, $result);
    }
}
