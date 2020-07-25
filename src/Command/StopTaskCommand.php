<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Interfaces\TaskSpanRepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;

class StopTaskCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'task:stop';

    public function __construct(TaskSpanRepositoryInterface $taskSpanRepository)
    {
        $this->taskSpanRepository = $taskSpanRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console task:stop"
        ->setDescription('Stop the timer.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This commands will stop timer, it takes in argument the name of a task, if it is the task actually running, we stop it.')
        
        ->addArgument('taskname', InputArgument::REQUIRED, 'The name of the task.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopped = $this->taskSpanRepository->stopRunningTaskSpanFromCommandLine($input->getArgument('taskname'));

        if($stopped)
        {
            $output->writeln('Task stopped.');
        }
        else
        {
            $output->writeln('The task did not stopped, may be it wasn\'t the good task name ?');
        }

        return 0;
    }
}