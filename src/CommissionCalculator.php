<?php

namespace App;

use App\Interface\BinProviderInterface;
use App\Interface\CountryCommissionPolicyInterface;
use App\Policy\AmountFixedCommissionPolicy;
use App\Policy\DefaultCountryCommissionPolicy;
use App\ValueObject\CurrencyPair;

readonly class CommissionCalculator
{
    /**
     * @param BinProviderInterface               $binProvider
     * @param AmountFixedCommissionPolicy        $amountFixedCommissionPolicy
     * @param DefaultCountryCommissionPolicy     $defaultCountryCommissionPolicy
     * @param CountryCommissionPolicyInterface[] $countryCommissionPolicies
     */
    public function __construct(
        private BinProviderInterface           $binProvider,
        private AmountFixedCommissionPolicy    $amountFixedCommissionPolicy,
        private DefaultCountryCommissionPolicy $defaultCountryCommissionPolicy,
        private array                          $countryCommissionPolicies
    ) {

    }

    /**
     * Calculate commission for transaction.
     *
     * @param string $transaction
     * @return float
     */

    /**
     * Calculate commission for transaction.
     *
     * @param string $transaction
     * @param CurrencyPairCollection $currencyPairCollection
     * @return float
     *
     * @throws \Exception
     */
    public function calculateCommission(string $transaction, CurrencyPairCollection $currencyPairCollection): float
    {
        $transactionData = json_decode($transaction, true);

        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(sprintf("Value %s is not a json format", $transaction));
        }

        $bin      = $transactionData['bin'];
        $amount   = $transactionData['amount'];
        $currency = $transactionData['currency'];

        $country    = $this->binProvider->getCountry($bin);

        $needlePair = array_filter(
            $currencyPairCollection->toArray(),
            fn(CurrencyPair $pair) => $pair->getCounterCurrency()->getName() == $currency
        );

        /**
         * @var $pair CurrencyPair
         */
        $pair = array_shift($needlePair);

        if (empty($pair) || $pair instanceof CurrencyPair === false) {
            throw new \Exception(sprintf('Wrong pair (%s) for currency (%s)', print_r($pair, 1), $currency));
        }

        return $this->getCommissionPolicy($country)
            ->calculateCommission(
                $this->amountFixedCommissionPolicy->calculateCommission($amount, $currency, $pair->getRatio())
            );
    }

    /**
     * Get commission policy by country code
     *
     * @param string $country
     * @return CountryCommissionPolicyInterface
     */
    private function getCommissionPolicy(string $country): CountryCommissionPolicyInterface
    {
        foreach ($this->countryCommissionPolicies as $policy) {
            if ($policy->isApplicable($country)) {
                return $policy;
            }
        }

        return $this->defaultCountryCommissionPolicy;
    }
}