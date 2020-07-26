<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\TaskSpan;
use App\Repository\TaskSpanRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Interfaces\TaskRepositoryInterface;


/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, TaskSpanRepository $taskSpanRepository)
    {
        parent::__construct($registry, Task::class);
        $this->taskSpanRepository = $taskSpanRepository;
    }

    // Check if the task already exists, searching from it's name value
    public function generateNewTask($task)
    {
        $em = $this->getEntityManager();

        $existingTask = $this->findBy(array('name' => $task->getName()));

        $taskSpan = new TaskSpan;

        // If there is already a task with the name sent, we use it, if not, we create a new one
        if (!count($existingTask))
        {
            $task->addTaskSpan($taskSpan);
            $em->persist($task);
        }
        else
        {
            $existingTask[0]->addTaskSpan($taskSpan);
        }

        // If a previous taskSpan hasn't be stopped, we stop it
        $this->taskSpanRepository->stopRunningTaskSpan();

        $em->persist($taskSpan);
    }


    public function generateNewTaskFromCommandLine($taskName)
    {
        $em = $this->getEntityManager();

        $task = new Task;
        $task->setName($taskName);
        $this->generateNewTask($task);
        $em->flush();
    }
}
