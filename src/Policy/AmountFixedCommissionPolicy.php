<?php

namespace App\Policy;

readonly class AmountFixedCommissionPolicy
{
    public function __construct(private string $commissionCurrency = 'EUR') {

    }

    public function calculateCommission(float $amount, string $currency, float $rate): float
    {
        if ($currency !== $this->commissionCurrency && $rate != 0) {
            $amount /= $rate;
        }

        return round($amount, 2);
    }
}