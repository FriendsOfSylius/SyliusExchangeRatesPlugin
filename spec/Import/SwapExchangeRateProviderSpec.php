<?php

namespace spec\FriendsOfSylius\SyliusExchangeRatePlugin\Import;

use FriendsOfSylius\SyliusExchangeRatePlugin\Import\SwapExchangeRateProvider;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\ExchangeRateProviderInterface;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\ExchangeRateProviderException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swap\Exception\Exception;
use Swap\Model\Rate;
use Swap\SwapInterface;

class SwapExchangeRateProviderSpec extends ObjectBehavior
{
    function let(SwapInterface $swap)
    {
        $this->beConstructedWith($swap);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SwapExchangeRateProvider::class);
    }
    
    function it_is_an_exchange_rate_provider()
    {
        $this->shouldImplement(ExchangeRateProviderInterface::class);
    }

    function it_gets_the_ratio_from_the_swap_library(SwapInterface $swap)
    {
        $swap->quote('EUR/PLN')->willReturn(new Rate(4.15));

        $this->getRatio('EUR', 'PLN')->shouldReturn(4.15);
    }

    function it_throws_an_unsupported_currency_exception(SwapInterface $swap)
    {
        $swap->quote('EUR/ATS')->willThrow(new Exception('Something went wrong'));

        $this
            ->shouldThrow(new ExchangeRateProviderException('Something went wrong'))
            ->during('getRatio', ['EUR', 'ATS'])
        ;

    }
}
