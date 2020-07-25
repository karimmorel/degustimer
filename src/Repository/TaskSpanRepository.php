<?php

namespace App\Repository;

use App\Entity\TaskSpan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Interfaces\TaskSpanRepositoryInterface;

/**
 * @method TaskSpan|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskSpan|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskSpan[]    findAll()
 * @method TaskSpan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskSpanRepository extends ServiceEntityRepository implements TaskSpanRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskSpan::class);
    }

    public function getRunningTaskSpan()
    {
        return $this->findOneBy(array('stoped_at' => null));
        
    }

    // Only one task should be running at the same time, so I get all the tasks which hasn't been ended, and I stop them.
    public function stopRunningTaskSpan()
    {
        $runningTask = $this->getRunningTaskSpan();

        if($runningTask)
        {
            $runningTask->stop();
        }
    }

    // As requested, for the command line I take in argument the name of the task, but I think it is not necessary because there is only one task running at a time
    // That's why I did a different method for the command line event running stopRunningTaskSpan()
    public function stopRunningTaskSpanFromCommandLine($taskName)
    {
        $em = $this->getEntityManager();
        $return = false;
        
        $runningTask = $this->getRunningTaskSpan();
        if($runningTask)
        {
            if($taskName == $runningTask->getTask()->getName())
            {
                $runningTask->stop();
                $return = true;
            }
        }

        $em->flush();
        return $return;
    }

    // Get TaskSpans only with a value on stopped_by
    public function findEndedTaskSpans()
    {
        return $this->createQueryBuilder('ts')
        ->andWhere('ts.stoped_at IS NOT NULL')
        ->getQuery()
        ->getResult();
    }

    // Return the summary of all the ended tasks, sorted by day and with taskSpans regrouped by Task
    public function getSummary()
    {
        $taskSpanList = $this->findEndedTaskSpans();

        $date = null;
        $summary = array();
        $dailySummary = array();

        foreach ($taskSpanList as $taskSpan)
        {
            if($date == null)
            {
                $date = $taskSpan->getCreatedAt(); 
            }

            if($taskSpan->getCreatedAt()->format('d - m - Y') != $date->format('d - m - Y'))
            {
                $date = $taskSpan->getCreatedAt();
                $summary[$date->format('d - m - Y')] = $dailySummary;
                $dailySummary = array();
            }

            if(array_key_exists($taskSpan->getTask()->getName(), $dailySummary))
            {
                $e = new \DateTime;
                $f = clone $e;
                $e->add($dailySummary[$taskSpan->getTask()->getName()]);
                $e->add($taskSpan->getTaskSpanInterval());

                $interval = $f->diff($e);

                $dailySummary[$taskSpan->getTask()->getName()] = $interval;
            }
            else
            {
                $dailySummary[$taskSpan->getTask()->getName()] = $taskSpan->getTaskSpanInterval();
            }
        }
                $summary[$date->format('d - m - Y')] = $dailySummary;
                $dailySummary = array();

        return $summary;
    }

}
