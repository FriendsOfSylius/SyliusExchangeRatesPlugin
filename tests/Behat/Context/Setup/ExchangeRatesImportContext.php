<?php

namespace Tests\Acme\SyliusExamplePlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Acme\SyliusExamplePlugin\Import\ExchangeRatesImporterInterface;
use Acme\SyliusExamplePlugin\Import\TestExchangeRatesProviderInterface;

class ExchangeRatesImportContext implements Context
{
    private $exchangeRatesImporter;
    private $exchangeRatesProvider;

    public function __construct(
        ExchangeRatesImporterInterface $exchangeRatesImporter,
        TestExchangeRatesProviderInterface $exchangeRatesProvider
    ) {
        $this->exchangeRatesImporter = $exchangeRatesImporter;
        $this->exchangeRatesProvider = $exchangeRatesProvider;
    }

    /**
     * @Given reliable source set the exchange rate between :sourceCurrency and :targetCurrency to :ratio
     */
    public function reliableSourceSetTheExchangeRateBetweenAndTo($sourceCurrency, $targetCurrency, $ratio)
    {
        $this->exchangeRatesProvider->setRatioBetween($sourceCurrency, $targetCurrency, $ratio);
    }

    /**
     * @Given last night the store updated exchange rates based on this source
     */
    public function lastNightTheStoreUpdatedExchangeRatesBasedOnThisSource()
    {
        $this->exchangeRatesImporter->import();
    }
}
