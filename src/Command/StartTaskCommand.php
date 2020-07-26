<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Interfaces\TaskRepositoryInterface;
use Symfony\Component\Console\Input\InputArgument;

class StartTaskCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'task:start';

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console task:start"
        ->setDescription('Creates a new task and launch the timer.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This commands will create a new task if it does\'nt already exists, if not, it will use the existing one to start the timer.')
        
        ->addArgument('taskname', InputArgument::REQUIRED, 'The name of the task.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        date_default_timezone_set('Europe/Paris');
        
        $this->taskRepository->generateNewTaskFromCommandLine($input->getArgument('taskname'));

        $output->writeln('Task created.');

        return 0;
    }
}