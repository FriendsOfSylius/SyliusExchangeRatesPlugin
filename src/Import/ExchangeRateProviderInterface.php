<?php

namespace Acme\SyliusExamplePlugin\Import;

interface ExchangeRateProviderInterface
{

    public function getRatio($argument1, $argument2);
}
