<?php

namespace App\Client;

use App\CurrencyPairCollection;
use App\ValueObject\Currency;
use App\ValueObject\CurrencyPair;

abstract class BaseExchangeRatesApiProvider
{
    protected function getPairsFromResponse(array $response): CurrencyPairCollection
    {
        $result = [];
        foreach ($response['rates'] as $counterCurrency => $rate) {
            $result[] = new CurrencyPair(
                Currency::create($this->getBaseCurrency()),
                Currency::create($counterCurrency),
                (float)$rate
            );
        }

        return new CurrencyPairCollection($result);
    }

    public abstract function getBaseCurrency(): string;
}