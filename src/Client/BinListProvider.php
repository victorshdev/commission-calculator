<?php

namespace App\Client;

use App\Exception\WrongResponseFormatException;
use App\Interface\BinProviderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinListProvider implements BinProviderInterface
{
    /**
     * @param Client $httpClient
     * @param string $url
     */
    public function __construct(private readonly Client $httpClient, private string $url)
    {
        $this->url = trim($this->url, '/');
    }

    /**
     * Get country by bin.
     *
     * @throws GuzzleException
     * @throws WrongResponseFormatException
     */
    public function getCountry(string $bin): string
    {
        $response = $this->httpClient->get("{$this->url}/{$bin}");
        $content = $response->getBody()->getContents();
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE || false === isset($data['country'], $data['country']['alpha2'])) {
            throw new WrongResponseFormatException(sprintf('Wrong response format. Response is: %s', $content));
        }

        return $data['country']['alpha2'] ?? '';
    }
}