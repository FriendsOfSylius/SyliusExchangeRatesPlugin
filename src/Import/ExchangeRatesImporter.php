<?php

namespace Acme\SyliusExamplePlugin\Import;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Currency\Repository\ExchangeRateRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Currency\Model\ExchangeRateInterface;

class ExchangeRatesImporter implements ExchangeRatesImporterInterface
{
    /**
     * @var RepositoryInterface
     */
    private $currencyRepository;

    /**
     * @var ObjectManager
     */
    private $exchangeRateManager;

    /**
     * @var ExchangeRateProviderInterface
     */
    private $exchangeRateProvider;

    /**
     * @var ExchangeRateRepositoryInterface
     */
    private $exchangeRateRepository;

    /**
     * @var FactoryInterface
     */
    private $exchangeRateFactory;

    public function __construct(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        ExchangeRateProviderInterface $exchangeRateProvider,
        ExchangeRateRepositoryInterface $exchangeRateRepository,
        FactoryInterface $exchangeRateFactory
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->exchangeRateManager = $exchangeRateManager;
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->exchangeRateFactory = $exchangeRateFactory;
    }

    public function import()
    {
        $currencies = $this->currencyRepository->findAll();

        if (2 > count($currencies)) {
            return;
        }

        $codes = [$currencies[0]->getCode(), $currencies[1]->getCode()];
        $ratio = $this->exchangeRateProvider->getRatio($codes[0], $codes[1]);

        $exchangeRate = $this->exchangeRateRepository->findOneWithCurrencyPair($codes[0], $codes[1]);

        if (null === $exchangeRate) {
            /** @var ExchangeRateInterface $exchangeRate */
            $exchangeRate = $this->exchangeRateFactory->createNew();
            $exchangeRate->setSourceCurrency($currencies[0]);
            $exchangeRate->setTargetCurrency($currencies[1]);

            $this->exchangeRateManager->persist($exchangeRate);
        }

        $exchangeRate->setRatio($ratio);

        $this->exchangeRateManager->flush();
    }
}
