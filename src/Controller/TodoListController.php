<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TodoListController extends AbstractController
{
    /**
     * @Route("/todo/list", name="todo_list")
     */
    public function index(): Response
    {
        $task = $this->getDoctrine()->getRepository(Task::class)->findBy([], ['id'=> 'DESC']);

        return $this->render('index.html.twig', ['tasks' => $task]);
    }

    /**
     * @Route("/create", name="create_task", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $title = trim($request->request->get('title'));

        if(empty($title)) {
            return $this->redirectToRoute('todo_list');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task;
        $task->setTitle($title);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/switch-status/{id}", name="switch_status")
     */
    public function switchStatus($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $task->setStatus(!$task->getStatus());
        $entityManager->flush();

        return $this->redirectToRoute('todo_list');
    }

    /**
     * @Route("/delete/{id}", name="task_delete")
     */
    public function delete($id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $task = $entityManager->getRepository(Task::class)->find($id);

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('todo_list');
    }
}
