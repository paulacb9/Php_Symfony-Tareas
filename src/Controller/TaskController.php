<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{

    public function index(ManagerRegistry $registry): Response
    {
        // Prueba de entidades y relaciones
        $task_repo = $registry->getRepository(Task::class);
        $tasks = $task_repo->findBy([], ['id' => 'DESC']);

        /*
        $user_repo = $registry->getRepository(User::class);
        $users = $user_repo->findAll();

        foreach($users as $user){
            echo "<h1>{$user->getName()} {$user->getSurname()}</h1>";

            foreach($user->getTasks() as $task){
                echo $task->getTitle()."<br>";
            }
    
        }*/

        
        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    public function detail(Task $task){
        if(!$task){
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/detail.html.twig', [
            'task' => $task
        ]);
    }

    public function creation(Request $request, UserInterface $user, ManagerRegistry $registry){
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $task->setCreatedAt(new \DateTime('now'));
            $task->setUser($user);

            $registry->getManager()->persist($task);
            $registry->getManager()->flush();

            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function myTasks(UserInterface $user){
        $tasks = $user->getTasks();

        return $this->render('task/my-tasks.html.twig', [
            'tasks' => $tasks
        ]);
    }

    public function edit(Request $request, Task $task, UserInterface $user, ManagerRegistry $registry){
        if(!$user || $user->getId() != $task->getUser()->getId()){
            return $this->redirectToRoute('tasks');
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $registry->getManager()->persist($task);
            $registry->getManager()->flush();

            return $this->redirect($this->generateUrl('task_detail', ['id' => $task->getId()]));
        }

        return $this->render('task/creation.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }

    public function delete(Task $task, ManagerRegistry $registry, UserInterface $user){
        if(!$user || $user->getId() != $task->getUser()->getId()){
            return $this->redirectToRoute('tasks');
        }

        if(!$task){
            return $this->redirectToRoute('tasks');
        }

        $registry->getManager()->remove($task);
        $registry->getManager()->flush();

        return $this->redirectToRoute('tasks');
    }
}
