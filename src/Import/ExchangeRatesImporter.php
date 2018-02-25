<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Import;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Currency\Model\CurrencyInterface;
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

        $wasAnythingUpdated = false;
        $maxIterationCode = count($currencies) - 1;

        /** @var CurrencyInterface $sourceCurrency */
        foreach ($currencies as $i => $sourceCurrency) {
            for ($j = $i + 1; $j <= $maxIterationCode; ++$j) {
                $targetCurrency = $currencies[$j];

                if ($this->updateRatio($sourceCurrency, $targetCurrency)) {
                    $wasAnythingUpdated = true;
                }
            }
        }

        if ($wasAnythingUpdated) {
            $this->exchangeRateManager->flush();
        }
    }

    private function getExchangeRateForPair($sourceCurrencyCode, $targetCurrencyCode, $sourceCurrency, $targetCurrency)
    {
        $exchangeRate = $this->exchangeRateRepository->findOneWithCurrencyPair($sourceCurrencyCode, $targetCurrencyCode);

        if (null === $exchangeRate) {
            /** @var ExchangeRateInterface $exchangeRate */
            $exchangeRate = $this->exchangeRateFactory->createNew();
            $exchangeRate->setSourceCurrency($sourceCurrency);
            $exchangeRate->setTargetCurrency($targetCurrency);

            $this->exchangeRateManager->persist($exchangeRate);
        }
        return $exchangeRate;
    }

    private function updateRatio(CurrencyInterface $sourceCurrency, CurrencyInterface $targetCurrency)
    {
        $sourceCurrencyCode = $sourceCurrency->getCode();
        $targetCurrencyCode = $targetCurrency->getCode();

        try {
            $ratio = $this->exchangeRateProvider->getRatio($sourceCurrencyCode, $targetCurrencyCode);
        } catch (ExchangeRateProviderException $exception) {
            return false;
        }

        /** @var ExchangeRateInterface $exchangeRate */
        $exchangeRate = $this->getExchangeRateForPair($sourceCurrencyCode, $targetCurrencyCode, $sourceCurrency, $targetCurrency);

        $exchangeRate->setRatio($ratio);

        return true;
    }
}
