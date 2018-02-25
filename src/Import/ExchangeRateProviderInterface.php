<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Import;

interface ExchangeRateProviderInterface
{
    public function getRatio($sourceCurrency, $targetCurrency);
}
