<?php

namespace Varvaruk\PaymentsFeeCalculator\Service;

use RuntimeException;
use Varvaruk\PaymentsFeeCalculator\Service\Commission\CommissionCalculatorFactory;

/**
 * Class CSVProcessor
 * @package Varvaruk\PaymentsFeeCalculator\Service
 */
class CSVProcessor
{
    /**
     * @var CommissionCalculatorFactory
     */
    private CommissionCalculatorFactory $calculatorFactory;

    /**
     * CSVProcessor constructor.
     * @param CommissionCalculatorFactory $calculatorFactory
     */
    public function __construct(CommissionCalculatorFactory $calculatorFactory)
    {
        $this->calculatorFactory = $calculatorFactory;
    }

    /**
     * @param string $csvFilePath
     * @return array
     */
    public function processCSV(string $csvFilePath): array
    {
        $result = [];

        if (($handle = fopen($csvFilePath, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $operationDate = $data[0];
                $userId = (int)$data[1];
                $userType = $data[2];
                $operationType = $data[3] ?? '';
                $operationAmount = (float)$data[4];
                $operationCurrency = $data[5];

                // Calculate commission based on user type and operation type
                $commissionCalculator = $this->calculatorFactory->getCalculator($userType);
                $commission = $commissionCalculator->calculateCommission(
                    $operationDate,
                    $userId,
                    $operationAmount,
                    $operationCurrency,
                    $operationType
                );
                $result[] = $commission;
            }
            fclose($handle);
            return $result;
        }
        throw new RuntimeException("Failed to open CSV file: {$csvFilePath}");
    }
}
