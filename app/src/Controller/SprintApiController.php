<?php

namespace App\Controller;

use App\Entity\Sprint;
use App\Entity\Tasks;
use App\Form\AddTaskToSprintType;
use App\Form\SprintType;
use App\Form\StartSprintForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SprintApiController extends AbstractController
{
    /**
     * @Route("/api/sprints", name="create_sprint", methods={"POST"})
     */
    public function new(Request $request) : JsonResponse
    {
        $errors = [];
        $sprint = new Sprint();
        $form = $this->createForm(SprintType::class, $sprint);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            try {
                $id = $this->generateId($data->getWeek(),$data->getYear());
                $sprint->setId($id);
            }
            catch (\InvalidArgumentException $e)
            {
                $errors['Global'] = 'этот спринт уже существует!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sprint);
            $entityManager->flush();
            return new JsonResponse(['Id'=>$sprint->getId()]);
        }
        $errors['Fields'] = $this->getErrorsFromForm($form);
        return new JsonResponse(['Errors'=>$errors]);
    }

    /**
     * @Route("/api/sprints/add-task", name="add_task", methods={"POST"})
     */
    public function addTaskToSprint(Request $request) :JsonResponse
    {
        $errors = [];
        $form = $this->createForm(AddTaskToSprintType::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $sprint = $this->getDoctrine()->getRepository(Sprint::class)->findOneBy(array('id'=>$data['sprintId']));
            if (!$sprint){
                $errors['Global'] = 'спринт не найден!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            $task = $this->getDoctrine()->getRepository(Tasks::class)->findOneBy(array('id'=>$data['taskId']));
            if (!$task){
                $errors['Global'] = 'задача не найдена!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            $task->setProject($sprint);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(
                [
                    'success' => 'задача успешно добавлена в спринт'
                ]
            );
        }
        $errors['Fields'] = $this->getErrorsFromForm($form);
        return new JsonResponse(['Errors'=>$errors]);
    }


    /**
     * @Route("/api/sprints/start", name="add_task_to_sprint", methods={"POST"})
     */
    public function startSprint(Request $request):JsonResponse
    {
        $errors = [];
        $form = $this->createForm(StartSprintForm::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $working_sprints = $this->getDoctrine()->getRepository(Sprint::class)
                ->findOneBy(array('status'=>'IN_PROGRESS'));
            if ($working_sprints)
            {
                $errors['Global'] = 'Закройте рабочие спринты, прежде чем начинать новый!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            $data = $form->getData();
            $sprint = $this->getDoctrine()->getRepository(Sprint::class)->findOneBy(array('id'=>$data['sprintId']));
            if (!$sprint){
                $errors['Global'] = 'спринт не найден!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            try {
                if ($sprint->checkForStart())
                {
                    $sprint->setStatus('IN_PROGRESS');
                    $this->getDoctrine()->getManager()->flush();
                    return new JsonResponse(['success' => 'спринт успешно запущен']);
                }
            }
            catch (\Exception $e)
            {
                return new JsonResponse(
                    ['Global' => $e->getMessage()]
                );
            }
        }
        $errors['Fields'] = $this->getErrorsFromForm($form);
        return new JsonResponse(['Errors'=>$errors]);
    }

    /**
     * @Route("/api/sprints/close", name="close_sprint", methods={"POST"})
     */
    public function closeSprint(Request $request) : JsonResponse
    {
        $errors = [];
        $form = $this->createForm(StartSprintForm::class);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $sprint = $this->getDoctrine()->getRepository(Sprint::class)->findOneBy(array('id'=>$data['sprintId']));
            if (!$sprint){
                $errors['Global'] = 'спринт не найден!';
                return new JsonResponse(['Errors'=>$errors]);
            }
            try {
                if ($sprint->checkForClose())
                {
                    $sprint->setStatus('CLOSED');
                    $this->getDoctrine()->getManager()->flush();
                    return new JsonResponse(['success' => 'спринт успешно закрыт']);
                }
            }
            catch (\Exception $e)
            {
                return new JsonResponse(
                    ['Global' => $e->getMessage()]
                );
            }
        }
        $errors['Fields'] = $this->getErrorsFromForm($form);
        return new JsonResponse(['Errors'=>$errors]);
    }

    private function generateId($week, $year)
    {
        $id = ($year%100).'-'.$week;
        $previous_id = $this->getDoctrine()->getRepository(Sprint::class)->findOneBy(array('id'=>$id));
        if (!$previous_id)
        {
            return $id;
        }
        throw new InvalidArgumentException();
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
