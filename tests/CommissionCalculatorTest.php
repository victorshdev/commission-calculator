<?php

use App\CommissionCalculator;
use App\Interface\BinProviderInterface;
use App\Policy\EUCountryCommissionPolicy;
use App\Policy\DefaultCountryCommissionPolicy;
use PHPUnit\Framework\TestCase;
use App\Policy\AmountFixedCommissionPolicy;
use App\ValueObject\CurrencyPair;
use App\ValueObject\Currency;
use App\CurrencyPairCollection;

class CommissionCalculatorTest extends TestCase
{
    private CommissionCalculator $commissionCalculator;
    private BinProviderInterface $binProviderMock;

    public function setUp(): void
    {
        $this->binProviderMock = $this->createMock(BinProviderInterface::class);

        $this->commissionCalculator = new CommissionCalculator(
            $this->binProviderMock,
            new AmountFixedCommissionPolicy(),
            new DefaultCountryCommissionPolicy(),
            [new EUCountryCommissionPolicy()]
        );
    }

    /**
     * @dataProvider getTransactions
     *
     * @param string $input
     * @param float $expectedCommission
     * @param string $bin
     * @param string $country
     * @return void
     */
    public function testCalculateCommission(
        string $input,
        float $expectedCommission,
        string $bin,
        string $country,
        CurrencyPairCollection $currencyPairs
    ): void
    {
        $this->binProviderMock->expects($this->once())
            ->method('getCountry')
            ->with($bin)
            ->willReturn($country);

        $this->assertEquals(
            $expectedCommission,
            $this->commissionCalculator->calculateCommission($input, $currencyPairs)
        );
    }

    private function getTransactions(): array
    {
        return [
            [
                '{"bin":"45717360", "amount":"100.00", "currency":"EUR"}',
                1.0,
                '45717360',
                'DE',
                new CurrencyPairCollection([
                    new CurrencyPair(Currency::create('EUR'), Currency::create('USDT'), 1.0),
                    new CurrencyPair(Currency::create('EUR'), Currency::create('EUR'), 1.0),
                ])
            ],
            [
                '{"bin":"45717360", "amount":"100.00", "currency":"USD"}',
                1.9,
                '45717360',
                'UA',
                new CurrencyPairCollection([
                    new CurrencyPair(Currency::create('EUR'), Currency::create('USDT'), 1.0),
                    new CurrencyPair(Currency::create('EUR'), Currency::create('USD'), 1.05),
                ])
            ],
        ];
    }
}