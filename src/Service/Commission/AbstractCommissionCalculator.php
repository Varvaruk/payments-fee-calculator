<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

use RuntimeException;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverter;

/**
 * Class AbstractCommissionCalculator
 * @package Varvaruk\PaymentsFeeCalculator\Service\Commission
 */
abstract class AbstractCommissionCalculator implements CommissionCalculatorInterface
{
    /**
     * PRIVATE_CLIENT_TYPE
     */
    public const PRIVATE_CLIENT_TYPE = 'private';
    /**
     * BUSINESS_CLIENT_TYPE
     */
    public const BUSINESS_CLIENT_TYPE = 'business';
    /**
     * OPERATION_CURRENCY_EUR
     */
    public const OPERATION_CURRENCY_EUR = 'EUR';
    /**
     * DEPOSIT_COMMISSION_RATE
     */
    private const DEPOSIT_COMMISSION_RATE = 0.0003; // 0.03% for deposits
    /**
     * DEPOSIT_OPERATION_TYPE
     */
    private const DEPOSIT_OPERATION_TYPE = 'deposit';
    /**
     * WITHDRAW_OPERATION_TYPE
     */
    private const WITHDRAW_OPERATION_TYPE = 'withdraw';

    /**
     * @param string $operationDate
     * @param int $userId
     * @param float $operationAmount
     * @param string $operationCurrency
     * @return string|int|float
     */
    abstract public function calculateWithdrawCommission(
        string $operationDate,
        int $userId,
        float $operationAmount,
        string $operationCurrency
    ): string|int|float;

    /**
     * @param string $operationDate
     * @param int $userId
     * @param float $operationAmount
     * @param string $operationCurrency
     * @param string $operationType
     * @return string|int|float
     */
    public function calculateCommission(
        string $operationDate,
        int $userId,
        float $operationAmount,
        string $operationCurrency,
        string $operationType
    ): string|int|float {
        return match ($operationType) {
            self::DEPOSIT_OPERATION_TYPE => $this->calculateDepositCommission($operationAmount, $operationCurrency),
            self::WITHDRAW_OPERATION_TYPE => $this->calculateWithdrawCommission(
                $operationDate,
                $userId,
                $operationAmount,
                $operationCurrency
            ),
            default => throw new RuntimeException("Invalid operation type: $operationType"),
        };
    }

    /**
     * @param float $amount
     * @param string $operationCurrency
     * @return string|int|float
     */
    public function calculateDepositCommission(float $amount, string $operationCurrency): string|int|float
    {
        // Calculate commission for deposits
        $commission = $amount * self::DEPOSIT_COMMISSION_RATE;
        return $this->formattingDataTypes($commission, $operationCurrency);
    }

    /**
     * @param float $amount
     * @param string $operationCurrency
     * @return string|int|float
     */
    public function formattingDataTypes(float $amount, string $operationCurrency): string|int|float
    {
        // Check if the currency has decimal cents (e.g., JPY does not have decimal cents)
        $hasDecimalCents = !in_array($operationCurrency, CurrencyConverter::CURRENCIES_WITHOUT_CENTS);
        // If the currency does not have decimal cents, round the commission to the nearest whole number
        if (!$hasDecimalCents) {
            return ceil($amount);
        }

        // If the currency has decimal cents, round the commission to two decimal places
        $commission = ceil($amount * 100) / 100;
        return number_format($commission, 2);
    }
}
