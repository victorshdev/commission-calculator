<?php

namespace App\Interface;

use App\CurrencyPairCollection;

interface CurrencyRatesProviderInterface
{
    /**
     * @return CurrencyPairCollection
     */
    public function getExchangeRates(): CurrencyPairCollection;
}