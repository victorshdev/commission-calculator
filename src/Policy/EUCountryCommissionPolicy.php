<?php

namespace App\Policy;

use App\Interface\CountryCommissionPolicyInterface;

class EUCountryCommissionPolicy implements CountryCommissionPolicyInterface
{
    /**
     * @var array|string[]
     *
     * @todo: load from config?
     */
    private array $applicableCountries = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR',
        'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO',
        'PT', 'RO', 'SE', 'SI', 'SK'
    ];

    public function calculateCommission(float $amount): float
    {
        return round($amount * 0.01, 2);
    }

    public function isApplicable(string $country): bool
    {
        return in_array($country, $this->applicableCountries);
    }
}