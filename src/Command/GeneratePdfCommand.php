<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GeneratePdfCommand extends Command
{
    protected static $defaultName = 'app:generate-pdf';

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate pending pdf\'s projet')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Configure action
        /*
         * 1. Get the pending project on the db
         * 2. Foreach create pdf
         * 3. Check for timeout
         */
        $output->writeln('ça marche');
    }
}
