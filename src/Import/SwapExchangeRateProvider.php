<?php

namespace Acme\SyliusExamplePlugin\Import;

use Swap\Exception\Exception;
use Swap\SwapInterface;

class SwapExchangeRateProvider implements ExchangeRateProviderInterface
{
    /**
     * @var SwapInterface
     */
    private $swap;

    public function __construct(SwapInterface $swap)
    {
        $this->swap = $swap;
    }

    public function getRatio($sourceCurrency, $targetCurrency)
    {
        try {
            return (float) $this->swap->quote($sourceCurrency.'/'.$targetCurrency)->getValue();
        } catch (Exception $swapException) {
            throw new ExchangeRateProviderException($swapException->getMessage());
        }
    }
}
