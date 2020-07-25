<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\TaskSpanRepository;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{

    public function __construct (TaskRepository $taskRepository, TaskSpanRepository $taskSpanRepository)
    {
        $this->taskRepository = $taskRepository;
        $this->taskSpanRepository = $taskSpanRepository;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        // Creating the TaskType form with an entity Task
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Get task's summary
        $summary = $this->taskSpanRepository->getSummary();

        if ($form->isSubmitted() && $form->isValid())
        {
            // Check if the task already exists
            $this->taskRepository->generateNewTask($task);
            $this->addFlash('message', 'New task saved');
            
            return $this->redirectToRoute('home');
        }

        // View
        return $this->render('home/index.html.twig', 
        array('controller_name' => 'HomeController',
         'form' => $form->createView()
         ,'summary' => $summary)
    );
    }
}
