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
        // Мокуємо CommissionCalculatorFactory
        $calculatorFactoryMock = $this->createMock(CommissionCalculatorFactory::class);

        // Мокуємо CommissionCalculatorInterface для різних типів користувачів
        $commissionCalculatorMock1 = $this->createMock(CommissionCalculatorInterface::class);
        $commissionCalculatorMock1->method('calculateCommission')->willReturn(5.0);

        $commissionCalculatorMock2 = $this->createMock(CommissionCalculatorInterface::class);
        $commissionCalculatorMock2->method('calculateCommission')->willReturn(10.0);

        // Очікування виклику методу getCalculator з певними параметрами користувачів
        $calculatorFactoryMock->expects($this->exactly(2))
            ->method('getCalculator')
            ->withConsecutive(['test_user_type'], ['another_user_type'])
            ->willReturnOnConsecutiveCalls($commissionCalculatorMock1, $commissionCalculatorMock2);

        // Створення об'єкту CSVProcessor з мокованим CommissionCalculatorFactory
        $csvProcessor = new CSVProcessor($calculatorFactoryMock);

        $csvFilePath = __DIR__ . '/../fixtures/data.csv';

        // Виклик методу processCSV
        $result = $csvProcessor->processCSV($csvFilePath);

        // Перевірка результатів (змініть за потреби)
        $expectedResult = [5.0, 10.0];
        $this->assertEquals($expectedResult, $result);
    }
}
