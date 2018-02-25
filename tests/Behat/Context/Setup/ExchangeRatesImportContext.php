<?php

namespace Tests\FriendsOfSylius\SyliusExchangeRatePlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\ExchangeRatesImporterInterface;
use FriendsOfSylius\SyliusExchangeRatePlugin\Import\TestExchangeRateProviderInterface;
use Behat\Gherkin\Node\TableNode;

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
     * @Given reliable source set the following exchanges rates:
     */
    public function reliableSourceSetTheFollowingExchangesRates(TableNode $table)
    {
        foreach ($table->getHash() as $exchangeRate) {
           $this->reliableSourceSetTheExchangeRateBetweenAndTo($exchangeRate['from'], $exchangeRate['to'], $exchangeRate['ratio']);
        }
    }

    /**
     * @Given last night the store updated exchange rates based on this source
     */
    public function lastNightTheStoreUpdatedExchangeRatesBasedOnThisSource()
    {
        $this->exchangeRatesImporter->import();
    }
}
