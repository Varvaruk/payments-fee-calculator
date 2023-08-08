<?php

namespace Varvaruk\PaymentsFeeCalculator\Service\Commission;

/**
 * Class CommissionCalculatorFactory
 * @package Varvaruk\PaymentsFeeCalculator\Service\Commission
 */
class CommissionCalculatorFactory
{
    /**
     * @param string $clientType
     * @return CommissionCalculatorInterface
     */
    public function getCalculator(string $clientType): CommissionCalculatorInterface
    {
        return match ($clientType) {
            AbstractCommissionCalculator::PRIVATE_CLIENT_TYPE => PrivateClientCommissionCalculator::getInstance(),
            AbstractCommissionCalculator::BUSINESS_CLIENT_TYPE => BusinessClientCommissionCalculator::getInstance(),
            default => throw new \InvalidArgumentException('Invalid client type: ' . $clientType),
        };
    }
}
