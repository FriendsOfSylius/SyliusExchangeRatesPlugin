<?php

namespace Acme\SyliusExamplePlugin\Import;

interface ExchangeRateProviderInterface
{
    public function getRatio($sourceCurrency, $targetCurrency);
}
