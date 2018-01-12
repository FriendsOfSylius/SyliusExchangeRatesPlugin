<?php

namespace Acme\SyliusExamplePlugin\Import;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Currency\Repository\ExchangeRateRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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

    public function __construct(
        RepositoryInterface $currencyRepository,
        ObjectManager $exchangeRateManager,
        ExchangeRateProviderInterface $exchangeRateProvider,
        ExchangeRateRepositoryInterface $exchangeRateRepository
    ) {
        $this->currencyRepository = $currencyRepository;
        $this->exchangeRateManager = $exchangeRateManager;
        $this->exchangeRateProvider = $exchangeRateProvider;
        $this->exchangeRateRepository = $exchangeRateRepository;
    }

    public function import()
    {
        $currencies = $this->currencyRepository->findAll();

        if (empty($currencies)) {
            return;
        }

        $codes = [$currencies[0]->getCode(), $currencies[1]->getCode()];
        $ratio = $this->exchangeRateProvider->getRatio($codes[0], $codes[1]);

        $exchangeRate = $this->exchangeRateRepository->findOneWithCurrencyPair($codes[0], $codes[1]);
        $exchangeRate->setRatio($ratio);

        $this->exchangeRateManager->flush();
    }
}
