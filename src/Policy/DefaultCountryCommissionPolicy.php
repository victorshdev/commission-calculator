<?php

namespace App\Policy;

use App\Interface\CountryCommissionPolicyInterface;

class DefaultCountryCommissionPolicy implements CountryCommissionPolicyInterface
{
    public function calculateCommission(float $amount): float
    {
        return round($amount * 0.02, 2);
    }

    public function isApplicable(string $country): bool
    {
        return true;
    }
}