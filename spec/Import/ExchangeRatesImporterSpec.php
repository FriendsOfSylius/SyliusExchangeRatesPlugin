<?php

namespace spec\Acme\SyliusExamplePlugin\Import;

use Acme\SyliusExamplePlugin\Import\ExchangeRatesImporter;
use Acme\SyliusExamplePlugin\Import\ExchangeRatesImporterInterface;
use Acme\SyliusExamplePlugin\Import\ExchangeRateProviderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Currency\Model\ExchangeRateInterface;
use Sylius\Component\Currency\Repository\ExchangeRateRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class ExchangeRatesImporterSpec extends ObjectBehavior
{
    function let(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        ExchangeRateProviderInterface $exchangeRateProvider,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        FactoryInterface $exchangeRateFactory
    ) {
        $this->beConstructedWith($currencyRepository, $exchangeRateManager, $exchangeRateProvider, $exchangeRateRepository, $exchangeRateFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ExchangeRatesImporter::class);
    }
    
    function it_is_an_exchange_rates_importer()
    {
        $this->shouldImplement(ExchangeRatesImporterInterface::class);
    }

    function it_does_not_update_anything_if_there_are_no_currencies_configured(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager
    ) {
        $currencyRepository->findAll()->willReturn([]);

        $exchangeRateManager->flush()->shouldNotBeCalled();

        $this->import();
    }

    function it_does_not_update_anything_if_there_is_only_one_currency_configured(

        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        CurrencyInterface $euro
    ) {
        $currencyRepository->findAll()->willReturn([$euro]);

        $exchangeRateManager->flush()->shouldNotBeCalled();

        $this->import();
    }

    function it_updates_the_exchange_ratio_of_two_currencies_based_on_a_provider(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        CurrencyInterface $euro,
        CurrencyInterface $swissFranc,
        ExchangeRateProviderInterface $exchangeRateProvider,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        ExchangeRateInterface $exchangeRate
    ) {
        $currencyRepository->findAll()->willReturn([$euro, $swissFranc]);
        $euro->getCode()->willReturn('EUR');
        $swissFranc->getCode()->willReturn('CHF');
        
        $exchangeRateProvider->getRatio('EUR', 'CHF')->willReturn(1.17);

        $exchangeRateRepository->findOneWithCurrencyPair('EUR', 'CHF')->willReturn($exchangeRate);

        $exchangeRate->setRatio(1.17)->shouldBeCalled();
        $exchangeRateManager->flush()->shouldBeCalled();

        $this->import();
    }

    function it_creates_an_exchange_rate_if_it_does_not_exist_yet(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        CurrencyInterface $euro,
        CurrencyInterface $swissFranc,
        ExchangeRateProviderInterface $exchangeRateProvider,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        ExchangeRateInterface $exchangeRate,
        FactoryInterface $exchangeRateFactory
    ) {
        $currencyRepository->findAll()->willReturn([$euro, $swissFranc]);
        $euro->getCode()->willReturn('EUR');
        $swissFranc->getCode()->willReturn('CHF');

        $exchangeRateProvider->getRatio('EUR', 'CHF')->willReturn(1.17);

        $exchangeRateRepository->findOneWithCurrencyPair('EUR', 'CHF')->willReturn(null);
        $exchangeRateFactory->createNew()->willReturn($exchangeRate);

        $exchangeRate->setSourceCurrency($euro)->shouldBeCalled();
        $exchangeRate->setTargetCurrency($swissFranc)->shouldBeCalled();
        $exchangeRate->setRatio(1.17)->shouldBeCalled();

        $exchangeRateManager->persist($exchangeRate)->shouldBeCalled();
        $exchangeRateManager->flush()->shouldBeCalled();

        $this->import();
    }
}
