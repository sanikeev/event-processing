<?php

namespace App\Command;

use App\Message\EventMessage;
use App\Service\Sender;
use DateTime;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EventGenerateCommand extends Command
{
    protected static $defaultName = 'event:generate';
    /**
     * @var Sender
     */
    private Sender $sender;

    /**
     * EventGenerateCommand constructor.
     * @param Sender $sender
     */
    public function __construct(Sender $sender)
    {
        $this->sender = $sender;

        parent::__construct();
    }

    /**
     * Configure
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Generates series of test data')
            ->addOption('accnum', null, InputOption::VALUE_OPTIONAL, 'Number of accounts', 10);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $eventsNumber = 10;

        $accountNumber = $input->getOption('accnum');

        for ($i = 1; $i <= $accountNumber; $i++) {
            for ($n = 0; $n < $eventsNumber; $n++) {
                $message = new EventMessage(
                    $i,
                    $n.' some random string '.uniqid('', true),
                    new DateTime()
                );

                $this->sender->send($message);
            }
        }

        $io->success('All data successfully generated');

        return Command::SUCCESS;
    }
}
