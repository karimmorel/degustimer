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
use Symfony\Component\HttpFoundation\Response;

class TaskController extends AbstractController
{

    public function __construct (TaskRepositoryInterface $taskRepository, TaskSpanRepositoryInterface $taskSpanRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->taskSpanRepository = $taskSpanRepository;
    }


    public function create(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Creating the TaskType form with an entity Task
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Get running task before stopping it
            $runningTask = $this->taskSpanRepository->getRunningTaskSpan();

            // Check if the task already exists and create a new TaskSpan
            $this->taskRepository->generateNewTask($task);
            $entityManager->flush();

            // View or Ajax
            if ($request->isXmlHttpRequest())
            {
                $response = new Response();
                $taskName = $task->getName();
                if(strlen($taskName) > 18)
                {
                    $taskName = substr($taskName, 0, 18).'...';
                }

                $jsonArray = [
                    'message' => 'Task created',
                    'task_name' => $taskName,
                    'task_created' => $this->taskSpanRepository->getRunningTaskSpan()->getFormatedCreatedAt()
                ];

                if($runningTask)
                {
                    $jsonArray['taskinsummary'] = $this->taskSpanRepository->getNewTaskInView($runningTask);
                }

                $response->setContent(json_encode($jsonArray));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            // Notification
            $this->addFlash('message', 'New task saved : '.$task->getName());
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
            // Get running task before stopping it
            $runningTask = $this->taskSpanRepository->getRunningTaskSpan();

            // Check if the task already exists and create a new TaskSpan
            $this->taskSpanRepository->stopRunningTaskSpan();
            $this->addFlash('message', 'Task stopped');
            $entityManager->flush();

            // View or Ajax
            if ($request->isXmlHttpRequest())
            {
                $response = new Response();

                $jsonArray = [
                    'message' => 'Task stopped'
                ];

                if($runningTask)
                {
                    $jsonArray['taskinsummary'] = $this->taskSpanRepository->getNewTaskInView($runningTask);
                }

                $response->setContent(json_encode($jsonArray));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            // Notification
            $this->addFlash('message', 'Task stopped');
        }

        return $this->redirectToRoute('home');
    }
}
