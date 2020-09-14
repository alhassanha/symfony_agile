<?php


namespace App\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;



class TaskIdGenerator extends AbstractIdGenerator
{
    public function generate(EntityManager $em, $entity)
    {
        $entity_name = $em->getClassMetadata(get_class($entity))->getName();
        $result = $em->getRepository($entity_name)->findBy([], ['id'=>'DESC'], 1, 0);
        if (!$result)
        {
            return 'Task-1';
        }
        $last_task_id = (int)explode("-", $result[0]->getId())[1];
        return 'Task-'.($last_task_id+1);
    }
    public function isPostInsertGenerator()
    {
        return false;
    }
}