<?php

use PHPUnit\Framework\TestCase;
use App\Policy\AmountFixedCommissionPolicy;

class AmountFixedCommissionPolicyTest extends TestCase
{
    private AmountFixedCommissionPolicy $policy;

    public function setUp(): void
    {
        $this->policy = new AmountFixedCommissionPolicy();
    }

    /**
     * @dataProvider providerData
     *
     * @param float $amount
     * @param string $currency
     * @param float $rate
     * @param float $expectedCommission
     * @return void
     */
    public function testCalculateCommissionWithSameCurrency(
        float $amount,
        string $currency,
        float $rate,
        float $expectedCommission
    ) {
        $commission = $this->policy->calculateCommission($amount, $currency, $rate);

        $this->assertEquals($expectedCommission, $commission);
    }

    public function providerData(): array
    {
        return [
            [100.00, 'EUR', 1.0, 100.00],
            [100.00, 'USD', 1.2, 83.33],
            [100.00, 'USD', 0, 100],
        ];
    }
}