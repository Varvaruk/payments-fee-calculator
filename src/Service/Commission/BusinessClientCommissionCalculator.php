<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

/**
 * Class BusinessClientCommissionCalculator
 * @package Varvaruk\PaymentsFeeCalculator\Service\Commission
 */
class BusinessClientCommissionCalculator extends AbstractCommissionCalculator
{
    /**
     * @var BusinessClientCommissionCalculator $instance
     */
    private static $instance;
    /**
     * COMMISSION_RATE
     */
    public const COMMISSION_RATE = 0.005; // 0.5%

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $operationDate
     * @param int $userId
     * @param float $operationAmount
     * @param string $operationCurrency
     * @return string|int|float
     */
    public function calculateWithdrawCommission(
        string $operationDate,
        int $userId,
        float $operationAmount,
        string $operationCurrency
    ): string|int|float {
        $commission = $operationAmount * self::COMMISSION_RATE;
        return $this->formattingDataTypes($commission, $operationCurrency);
    }
}
