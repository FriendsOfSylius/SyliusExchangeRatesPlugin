<?php

namespace Acme\SyliusExamplePlugin\Import;

interface TestExchangeRateProviderInterface extends ExchangeRateProviderInterface
{
    public function setRatioBetween($sourceCurrency, $targetCurrency, $ratio);
}
