<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExchangeRatesController extends Controller
{
    public function importAction()
    {
        $this->get('sylius.exchange_rates_importer')->import();

        $this->addFlash('success', 'Exchange rates have been successfuly imported.');

        return $this->redirectToRoute('sylius_admin_exchange_rate_index');
    }
}
