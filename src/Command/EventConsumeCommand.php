<?php

namespace App\Command;

use App\Service\Receiver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EventConsumeCommand extends Command
{
    protected static $defaultName = 'event:consume';
    /**
     * @var Receiver
     */
    private $receiver;

    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Consume events for accounts')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->receiver->consume();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
