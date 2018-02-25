<?php

declare(strict_types=1);

namespace FriendsOfSylius\SyliusExchangeRatePlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class FOSSyliusExchangeRatePlugin extends Bundle
{
    use SyliusPluginTrait;
}
