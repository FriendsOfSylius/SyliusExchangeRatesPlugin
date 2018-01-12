<?php

namespace Acme\SyliusExamplePlugin\Import;

class TestExchangeRateProvider implements ExchangeRateProviderInterface, TestExchangeRateProviderInterface
{
    private $ratios = [];

    public function getRatio($sourceCurrency, $targetCurrency)
    {
        if (empty($this->ratios)) {
            throw new \LogicException('You first need to define the test ratios.');
        }

        return $this->ratios[$this->getPairId($sourceCurrency, $targetCurrency)];
    }

    public function setRatioBetween($sourceCurrency, $targetCurrency, $ratio)
    {
        $this->ratios[$this->getPairId($sourceCurrency, $targetCurrency)] = $ratio;
    }

    private function getPairId($sourceCurrency, $targetCurrency)
    {
        return $sourceCurrency.'/'.$targetCurrency;
    }
}
