<?php

namespace Acme\SyliusExamplePlugin\Import;

interface TestExchangeRateProviderInterface
{
    public function setRatioBetween($sourceCurrency, $targetCurrency, $ratio);
}
