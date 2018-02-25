<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Import;

interface TestExchangeRateProviderInterface extends ExchangeRateProviderInterface
{
    public function setRatioBetween($sourceCurrency, $targetCurrency, $ratio);
}
