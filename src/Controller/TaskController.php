<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\TaskSpanRepository;
use App\Form\TaskType;
use App\Form\StopTaskType;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    public function __construct (TaskRepository $taskRepository, TaskSpanRepository $taskSpanRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->taskSpanRepository = $taskSpanRepository;
    }

    /**
     * @Route("/task/create", name="task.create")
     */
    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Creating the TaskType form with an entity Task
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Check if the task already exists and create a new TaskSpan
            $this->taskRepository->generateNewTask($task);
            $this->addFlash('message', 'New task saved : '.$task->getName());
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }

    public function stop(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // I use a form for the "Stop" button to create an easy configured csrf_token to be sure that anybody can't stop it easily
        $form = $this->createForm(StopTaskType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Check if the task already exists and create a new TaskSpan
            $this->taskSpanRepository->stopRunningTask();
            $this->addFlash('message', 'Task stopped');
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
