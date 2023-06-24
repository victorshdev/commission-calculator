<?php
declare(strict_types=1);

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use App\Client\BinListProvider;
use App\Client\ExchangeRatesApiProvider;
use App\CommissionCalculator;
use App\Policy\EUCountryCommissionPolicy;
use App\Policy\AmountFixedCommissionPolicy;
use App\Policy\DefaultCountryCommissionPolicy;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$baseCurrency   = $_ENV['BASE_CURRENCY'];
$accessKey      = $_ENV['EXCHANGE_API_ACCESS_TOKEN'];
$binListUrl     = $_ENV['BIN_LIST_URL'];
$exchangeApiUrl = $_ENV['EXCHANGE_PROVIDER_URL'];
$source         = $argv[1];

$client        = new Client();
$binProvider   = new BinListProvider($client, $binListUrl);
$ratesProvider = new ExchangeRatesApiProvider($client, $exchangeApiUrl, $baseCurrency);

$currencyPairs = $ratesProvider->getExchangeRates();

$cc = new CommissionCalculator(
    $binProvider,
    new AmountFixedCommissionPolicy($baseCurrency),
    new DefaultCountryCommissionPolicy(),
    [new EUCountryCommissionPolicy()]);

$file = fopen($source, 'r');

if ($file) {
    while (($transaction = fgets($file)) !== false) {
        echo $cc->calculateCommission($transaction, $currencyPairs) . PHP_EOL;
    }

    fclose($file);
} else {
    echo "Failed to open the file.";
}
