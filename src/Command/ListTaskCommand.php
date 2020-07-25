<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Interfaces\TaskSpanRepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;

class ListTaskCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'task:list';

    public function __construct(TaskSpanRepositoryInterface $taskSpanRepository)
    {
        $this->taskSpanRepository = $taskSpanRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console task:list"
        ->setDescription('Get a list of all the tasks.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This commands will give you a list of all the tasks saved in the application, with some data related to each one of it.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $taskSpanList = $this->taskSpanRepository->findAll();

        foreach ($taskSpanList as $key => $taskSpan)
        {
            $status = $taskSpan->getStoppedAt() ? 'Ended' : 'Actually running';

            $output->writeln('Name : '.$taskSpan->getTask()->getName());
            $output->writeln('Status : '.$status);
            $output->writeln('Created : '.$taskSpan->getCreatedAt()->format('d - m - Y / h : i : s'));

            // Show these informations only if the task is actually running
            if($taskSpan->getStoppedAt())
            {
                $output->writeln('Ended : '.$taskSpan->getStoppedAt()->format('d - m - Y / h : i : s'));
                $output->writeln('Time elapsed : '.$taskSpan->getCreatedAt()->diff($taskSpan->getStoppedAt())->format('%h : %i : %s'));
            }
            
            if($key != count($taskSpanList)-1)
            {
                $output->writeln('============');
            }
        }

        return 0;
    }
}