<?php

namespace spec\FriendsOfSylius\SyliusExchangeRatePlugin\Import;

use FriendsOfSylius\SyliusExchangeRatePlugin\Import\TestExchangeRateProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\ExchangeRateProviderInterface;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\TestExchangeRateProviderInterface;

class TestExchangeRateProviderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TestExchangeRateProvider::class);
    }

    function it_is_for_testing_purposes()
    {
        $this->shouldImplement(TestExchangeRateProviderInterface::class);
    }

    function it_throws_an_exception_when_there_are_no_ratios_set()
    {
        $this
            ->shouldThrow(new \LogicException('You first need to define the test ratios.'))
            ->during('getRatio', ['EUR', 'USD'])
        ;
    }

    function it_returns_a_previously_set_test_ratio_for_a_pair_currency_codes()
    {
        $this->setRatioBetween('EUR', 'USD', 1.12);
        $this->getRatio('EUR', 'USD')->shouldReturn(1.12);
    }

    function it_throws_an_exception_when_there_is_no_ratio_set_for_a_specific_pair()
    {
        $this->setRatioBetween('EUR', 'USD', 1.12);
        $this->setRatioBetween('CHF', 'USD', 1.01);

        $this
            ->shouldThrow(new \InvalidArgumentException('There is no ratio set for CHF and EUR.'))
            ->during('getRatio', ['CHF', 'EUR'])
        ;
    }
}
