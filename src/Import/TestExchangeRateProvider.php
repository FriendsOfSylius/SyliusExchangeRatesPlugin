<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Import;

class TestExchangeRateProvider implements TestExchangeRateProviderInterface
{
    private $ratios = [];

    public function getRatio($sourceCurrency, $targetCurrency)
    {
        if (empty($this->ratios)) {
            throw new \LogicException('You first need to define the test ratios.');
        }

        if (!array_key_exists($this->getPairId($sourceCurrency, $targetCurrency), $this->ratios)) {
            throw new \InvalidArgumentException(sprintf('There is no ratio set for %s and %s.', $sourceCurrency, $targetCurrency));
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
