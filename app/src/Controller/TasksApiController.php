<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\EndTaskForm;
use App\Form\TaskEstimationFormType;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;


class TasksApiController extends AbstractController
{


    /**
     * @Route("/api/tasks", name="new_task", methods={"POST"})
     */
    public function new(Request $request): JsonResponse
    {
        $errors = [];
        $task = new Tasks();
        $form = $this->createForm(TaskType::class, $task);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
            return new JsonResponse(['id'=>$task->getId()]);
        }

        $errors['Fields'] = $this->getErrorsFromForm($form);
        return new JsonResponse(['Errors'=>$errors]);

    }

    /**
     * @Route("/api/estimate/task", name="estimate_task", methods={"POST"})
     */
    public function estimate(Request $request):JsonResponse{
        $form = $this->createForm(TaskEstimationFormType::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $task = $this->getDoctrine()->getRepository(Tasks::class)->findOneBy(array('id'=>$data['id']));
            if (!$task){
                $errors['Global'] = 'задача не найдена!';
            }
            else{
                $task->setEstimation($data['estimation']);
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse(
                    [
                        'success' => 'оценка успешно добавлена!'
                    ]
                );
            }
        }
        $field_errors = $this->getErrorsFromForm($form);
        if ($field_errors){
            $errors['Fields'] = $field_errors;
        }
        return new JsonResponse(['Errors'=>$errors]);
    }

    /**
     * @Route("/api/tasks/close", name="close_task", methods={"POST"})
     */
    public function closeTask(Request $request):JsonResponse
    {
        $form = $this->createForm(EndTaskForm::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $task = $this->getDoctrine()->getRepository(Tasks::class)->findOneBy(array('id'=>$data['taskId']));
            if (!$task){
                $errors['Global'] = 'задача не найдена!';
            }
            else{
                $task->setIsClosed(true);
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse(
                    [
                        'success' => 'Задача успешно закрыта!'
                    ]
                );
            }
        }
        $field_errors = $this->getErrorsFromForm($form);
        if ($field_errors){
            $errors['Fields'] = $field_errors;
        }
        return new JsonResponse(['Errors'=>$errors]);
    }


    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
