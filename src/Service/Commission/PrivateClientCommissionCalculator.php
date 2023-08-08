<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

use GuzzleHttp\Client;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverter;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyConverterInterface;
use Varvaruk\PaymentsFeeCalculator\Service\Currency\CurrencyExchangeRateApiClient;

/**
 * Class PrivateClientCommissionCalculator
 * @package Varvaruk\PaymentsFeeCalculator\Service\Commission
 */
class PrivateClientCommissionCalculator extends AbstractCommissionCalculator
{
    /**
     * @var PrivateClientCommissionCalculator $instance
     */
    private static $instance;
    /**
     * WEEKLY_FREE_WITHDRAW_LIMIT_EUR
     */
    private const WEEKLY_FREE_WITHDRAW_LIMIT_EUR = 1000.00;
    /**
     * WEEKLY_FREE_WITHDRAW_OPERATIONS
     */
    private const WEEKLY_FREE_WITHDRAW_OPERATIONS = 3;
    /**
     * COMMISSION_RATE
     */
    private const COMMISSION_RATE = 0.003; // 0.3%
    /**
     * @var array
     */
    private array $weeklyWithdrawals = [];
    /**
     * @var CurrencyConverterInterface
     */
    public CurrencyConverterInterface $currencyConverter;

    /**
     * PrivateClientCommissionCalculator constructor.
     * @param CurrencyConverterInterface $currencyConverter
     */
    public function __construct(CurrencyConverterInterface $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self(new CurrencyConverter(new CurrencyExchangeRateApiClient(new Client())));
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
        // Convert the operation amount to EUR if the currency is not EUR
        if ($operationCurrency !== self::OPERATION_CURRENCY_EUR) {
            $operationAmountInEUR = $this->currencyConverter->convert(
                $operationAmount,
                $operationCurrency,
                self::OPERATION_CURRENCY_EUR
            );
        } else {
            $operationAmountInEUR = $operationAmount;
        }
        $weeklyWithdrawalAmountsInEUR = $this->getWeeklyWithdrawalAmount(
            $operationDate,
            $userId,
            $operationAmountInEUR
        );
        $weeklyWithdrawalAmountInEUR = $weeklyWithdrawalAmountsInEUR['sum'];
        if ($weeklyWithdrawalAmountsInEUR['count'] <= self::WEEKLY_FREE_WITHDRAW_OPERATIONS) {
            if ($weeklyWithdrawalAmountInEUR <= self::WEEKLY_FREE_WITHDRAW_LIMIT_EUR) {
                return $this->calculate(0, $operationCurrency);//No commission.
            }
            $weeklyExcessAmountInEUR = max(
                0,
                $weeklyWithdrawalAmountInEUR - self::WEEKLY_FREE_WITHDRAW_LIMIT_EUR
            );
            if ($weeklyExcessAmountInEUR < $operationAmountInEUR) {
                $excessAmount = $this->currencyConverter->convert(
                    $weeklyExcessAmountInEUR,
                    self::OPERATION_CURRENCY_EUR,
                    $operationCurrency
                );
                return $this->calculate($excessAmount, $operationCurrency);
            }
        }
        return $this->calculate($operationAmount, $operationCurrency);
    }

    /**
     * @param $excessAmount
     * @param string $operationCurrency
     * @return string|int|float
     */
    private function calculate($excessAmount, string $operationCurrency): string|int|float
    {
        $commission = $excessAmount * self::COMMISSION_RATE; // 0.3% commission
        return $this->formattingDataTypes($commission, $operationCurrency);
    }

    /**
     * @param string $operationDate
     * @param int $userId
     * @param float $operationAmount
     * @return array
     */
    private function getWeeklyWithdrawalAmount(string $operationDate, int $userId, float $operationAmount): array
    {
        $dateInfo = date_parse($operationDate);
        $day = $dateInfo['day'];
        $dayOfWeek = date('N', strtotime($operationDate));
        $startDate = date(
            'Y-m-d',
            strtotime('-' . ($dayOfWeek - 1) . ' days', strtotime($operationDate))
        );
        $endDate = date('Y-m-d', strtotime('+' . (7 - $dayOfWeek) . ' days', strtotime($operationDate)));
        $weekKey = "$startDate - $endDate";

        if (!isset($this->weeklyWithdrawals[$userId][$weekKey][$day])) {
            $this->weeklyWithdrawals[$userId][$weekKey][$day] = 0.0;
        }
        $this->weeklyWithdrawals[$userId][$weekKey][$day] += $operationAmount;
        return [
            'sum' => array_sum($this->weeklyWithdrawals[$userId][$weekKey]),
            'count' => count($this->weeklyWithdrawals[$userId][$weekKey])
        ];
    }
}
