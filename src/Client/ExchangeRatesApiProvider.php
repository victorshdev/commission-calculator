<?php

namespace App\Client;

use App\CurrencyPairCollection;
use App\Exception\WrongResponseFormatException;
use App\Interface\CurrencyRatesProviderInterface;
use App\ValueObject\Currency;
use App\ValueObject\CurrencyPair;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ExchangeRatesApiProvider extends BaseExchangeRatesApiProvider implements CurrencyRatesProviderInterface
{
    public function __construct(
        private readonly Client $httpClient,
        private string $url,
        private readonly string $baseCurrency
    ) {
        $this->url = trim($this->url, '/');
    }

    /**
     * Fetch exchange rate and return by currency code.
     *
     * @throws GuzzleException
     * @throws WrongResponseFormatException
     */
    public function getExchangeRates(): CurrencyPairCollection
    {
        $response = $this->httpClient->get($this->url);
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || false === array_key_exists('rates', $data)) {
            throw new WrongResponseFormatException(sprintf('Wrong response format. Response is: %s', $content));
        }

        return $this->getPairsFromResponse($data);
    }

    public function getBaseCurrency(): string
    {
        return $this->baseCurrency;
    }
}