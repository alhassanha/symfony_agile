<?php

namespace App\Entity;

use App\Repository\SprintRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SprintRepository::class)
 */
class Sprint
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $week;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=255, options={"default":"CREATED"})
     */
    private $status = 'CREATED';

    /**
     * @ORM\OneToMany(targetEntity=Tasks::class, mappedBy="project")
     */
    private $tasks;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(int $week): self
    {
        $this->week = $week;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Tasks[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Tasks $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Tasks $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    private function checkForEstimatedTasks() : bool
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create()->where($expression->eq("estimation", null));
        $unestimated_tasks = $this->tasks->matching($criteria);
        if (!$unestimated_tasks->isEmpty()){
            throw new Exception('Невозможно начать спринт. Оцените все задачи!');
        }
        return true;
    }

    private function checkPeriodBeforeSprint() : bool
    {
        $sprint_start = new \DateTime();
        $sprint_start->setISODate($this->year, $this->week);
        $today = new \DateTime("today");
        if($sprint_start->diff($today, true)->days > 7)
        {
            throw new \Exception('Можно начать спринт толька за меньше 7 дней до даты его начала');
        }
        return true;
    }

    public function checkTotalHours() : bool
    {
        $total_minutes = 0;
        $all_task = $this->getTasks();
        if ($all_task->isEmpty())
        {
            throw new Exception('Невозможно начать пустой спринт!');
        }
        foreach ($this->getTasks() as $task)
        {
            $total_minutes = $total_minutes + $task->getEstimation();
        }
        if (($total_minutes/60) > 40)
        {
            throw new Exception('Суммарная оценка задач в спринте > 40 часо!');
        }
        return true;
    }

    public function checkForStart() : bool
    {
        if ($this->checkForEstimatedTasks() && $this->checkPeriodBeforeSprint()
            && $this->checkTotalHours())
        {
            return true;
        }
    }

    public function checkForClose() : bool
    {
        $expression = Criteria::expr();
        $criteria = Criteria::create()->where($expression->eq("is_closed", false));
        $unestimated_tasks = $this->tasks->matching($criteria);
        if (!$unestimated_tasks->isEmpty()){
            throw new Exception('Невозможно эакрыть спринт. закройте все задачи!');
        }
        return true;
    }


}
