<?php

namespace App\Entity;

use App\Repository\TableListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableListRepository::class)
 */
class TableList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ActionLog::class, mappedBy="tablename")
     */
    private $actionLogs;

    public function __construct()
    {
        $this->actionLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ActionLog[]
     */
    public function getActionLogs(): Collection
    {
        return $this->actionLogs;
    }

    public function addActionLog(ActionLog $actionLog): self
    {
        if (!$this->actionLogs->contains($actionLog)) {
            $this->actionLogs[] = $actionLog;
            $actionLog->setTablename($this);
        }

        return $this;
    }

    public function removeActionLog(ActionLog $actionLog): self
    {
        if ($this->actionLogs->removeElement($actionLog)) {
            // set the owning side to null (unless already changed)
            if ($actionLog->getTablename() === $this) {
                $actionLog->setTablename(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
