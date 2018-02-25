<?php

namespace FriendsOfSylius\SyliusExchangeRatePlugin\Command;

use FriendsOfSylius\SyliusExchangeRatePlugin\Import\ExchangeRatesImporterInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExchangeRatesCommand extends ContainerAwareCommand
{
    private $exchangeRateImporter;

    public function __construct(ExchangeRatesImporterInterface $exchangeRateImporter)
    {
        $this->exchangeRateImporter = $exchangeRateImporter;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Importing latest exchange rates...');
        $this->exchangeRateImporter->import();
        $output->writeln('Done!');
    }
}
