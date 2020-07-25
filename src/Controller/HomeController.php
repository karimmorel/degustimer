<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\TaskSpanRepositoryInterface;
use App\Form\TaskType;
use App\Form\StopTaskType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    public function __construct (TaskRepositoryInterface $taskRepository, TaskSpanRepositoryInterface $taskSpanRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->taskSpanRepository = $taskSpanRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        // Creating the TaskType form with an entity Task
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, array(
            'action' => $this->generateUrl('task.create'),
            'method' => 'POST'
        ));

        // Creating the "Stop" button to stop a task - Using a form will create a usefull csrf_token
        $stopTaskForm = $this->createForm(StopTaskType::class, null, array(
            'action' => $this->generateUrl('task.stop'),
            'method' => 'POST'
        ));

        // Get the Task actually running
        $runningTaskSpan = $this->taskSpanRepository->getRunningTaskSpan();

        // Get task's summary
        $summary = $this->taskSpanRepository->getSummary();

        // View
        return $this->render('home/index.html.twig', 
        array('controller_name' => 'HomeController',
         'form' => $form->createView(),
         'stopTaskForm' => $stopTaskForm->createView(),
         'summary' => $summary,
         'runningTaskSpan' => $runningTaskSpan
         )
    );
    }
}
