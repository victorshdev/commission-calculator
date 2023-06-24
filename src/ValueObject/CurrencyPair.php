<?php

namespace App\ValueObject;

final readonly class CurrencyPair
{
    /**
     * @param Currency $baseCurrency
     * @param Currency $counterCurrency
     * @param float    $ratio
     */
    public function __construct(
        private Currency $baseCurrency,
        private Currency $counterCurrency,
        private float    $ratio
    )
    {
        if ($this->ratio <= 0.0) {
            throw new \InvalidArgumentException('Incorrect ratio');
        }
    }

    /**
     * @return Currency
     */
    public function getBaseCurrency(): Currency
    {
        return $this->baseCurrency;
    }

    /**
     * @return Currency
     */
    public function getCounterCurrency(): Currency
    {
        return $this->counterCurrency;
    }

    /**
     * @return float
     */
    public function getRatio(): float
    {
        return $this->ratio;
    }
}