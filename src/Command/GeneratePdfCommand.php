<?php

namespace App\Command;

use App\Service\PendingPdfManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GeneratePdfCommand extends Command
{
    // Command callable
    protected static $defaultName = 'app:generate-pdf';
    private $pendingPdfManager;

    // Get the pending manager at instanciation
    public function __construct(PendingPdfManager $pendingPdfManager, $name = null)
    {
        $this->pendingPdfManager = $pendingPdfManager;
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
        $this->pendingPdfManager->generatePdf();
        $output->writeln('PDF generated!');
    }
}
