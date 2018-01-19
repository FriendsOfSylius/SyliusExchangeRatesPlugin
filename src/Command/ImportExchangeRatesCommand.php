<?php

namespace Acme\SyliusExamplePlugin\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportExchangeRatesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('acme:import-exchange-rates')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Importing latest exchange rates...');
        $this->getContainer()->get('app.exchange_rates_importer')->import();
        $output->writeln('Done!');
    }
}
