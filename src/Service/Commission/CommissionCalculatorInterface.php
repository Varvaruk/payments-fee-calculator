<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

/**
 * Interface CommissionCalculatorInterface
 * @package Varvaruk\PaymentsFeeCalculator\Service\Commission
 */
interface CommissionCalculatorInterface
{
    /**
     * Calculate the commission for the given amount in the specified currency.
     *
     * @param string $operationDate
     * @param int $userId
     * @param float $operationAmount
     * @param string $operationCurrency
     * @param string $operationType
     * @return string|int|float The calculated commission.
     */
    public function calculateCommission(
        string $operationDate,
        int $userId,
        float $operationAmount,
        string $operationCurrency,
        string $operationType
    ): string|int|float;
}
