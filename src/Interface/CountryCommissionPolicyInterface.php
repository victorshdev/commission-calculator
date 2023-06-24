<?php

namespace App\Interface;

interface CountryCommissionPolicyInterface
{
    public function calculateCommission(float $amount): float;
    public function isApplicable(string $country): bool;
}