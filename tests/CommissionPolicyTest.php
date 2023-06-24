<?php

use PHPUnit\Framework\TestCase;
use App\Policy\DefaultCountryCommissionPolicy;
use App\Policy\EUCountryCommissionPolicy;

class CommissionPolicyTest extends TestCase
{
    public function testDefaultCommissionPolicy()
    {
        $policy = new DefaultCountryCommissionPolicy();
        $amount = 100.00;
        $expectedCommission = 2.00;

        $commission = $policy->calculateCommission($amount);

        $this->assertEquals($expectedCommission, $commission);
    }

    public function testEUCommissionPolicy()
    {
        $policy = new EUCountryCommissionPolicy();
        $amount = 100.00;
        $expectedCommission = 1.00;

        $commission = $policy->calculateCommission($amount);

        $this->assertEquals($expectedCommission, $commission);
    }
}