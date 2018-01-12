<?php

namespace Tests\Acme\SyliusExamplePlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Acme\SyliusExamplePlugin\Import\ExchangeRatesImporterInterface;
use Acme\SyliusExamplePlugin\Import\TestExchangeRateProviderInterface;

class ExchangeRatesImportContext implements Context
{
    private $exchangeRatesImporter;
    private $exchangeRatesProvider;

    public function __construct(
        ExchangeRatesImporterInterface $exchangeRatesImporter,
        TestExchangeRateProviderInterface $exchangeRatesProvider
    ) {
        $this->exchangeRatesImporter = $exchangeRatesImporter;
        $this->exchangeRatesProvider = $exchangeRatesProvider;
    }

    /**
     * @Given reliable source set the exchange rate between :currencyA and :currencyB to :ratio
     */
    public function reliableSourceSetTheExchangeRateBetweenAndTo($currencyA, $currencyB, $ratio)
    {
        $this->exchangeRatesProvider->setRatioBetween($currencyA, $currencyB, $ratio);
    }

    /**
     * @Given last night the store updated exchange rates based on this source
     */
    public function lastNightTheStoreUpdatedExchangeRatesBasedOnThisSource()
    {
        $this->exchangeRatesImporter->import();
    }
}
