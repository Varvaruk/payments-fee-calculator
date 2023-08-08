<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverter;

abstract class AbstractCommissionCalculator implements CommissionCalculatorInterface
{
    public const PRIVATE_CLIENT_TYPE = 'private';
    public const BUSINESS_CLIENT_TYPE = 'business';
    public const OPERATION_CURRENCY_EUR = 'EUR';
    private const DEPOSIT_COMMISSION_RATE = 0.0003; // 0.03% for deposits
    private const DEPOSIT_OPERATION_TYPE = 'deposit';
    private const WITHDRAW_OPERATION_TYPE = 'withdraw';


    abstract public function calculateWithdrawCommission(
        string $operationDate,
        int $userId,
        float $operationAmount,
        string $operationCurrency
    ): string|int|float;

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
            default => throw new \RuntimeException("Invalid operation type: $operationType"),
        };
    }

    public function calculateDepositCommission(float $amount, string $operationCurrency): string|int|float
    {
        // Calculate commission for deposits
        $commission = $amount * self::DEPOSIT_COMMISSION_RATE;
        return $this->formattingDataTypes($commission, $operationCurrency);
    }

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
