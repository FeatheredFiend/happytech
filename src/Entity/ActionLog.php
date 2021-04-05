<?php

namespace App\Entity;

use App\Repository\ActionLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActionLogRepository::class)
 */
class ActionLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="actionLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=TableList::class, inversedBy="actionLogs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tablename;

    /**
     * @ORM\Column(type="integer")
     */
    private $rownumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTablename(): ?TableList
    {
        return $this->tablename;
    }

    public function setTablename(?TableList $tablename): self
    {
        $this->tablename = $tablename;

        return $this;
    }

    public function getRownumber(): ?int
    {
        return $this->rownumber;
    }

    public function setRownumber(int $rownumber): self
    {
        $this->rownumber = $rownumber;

        return $this;
    }

    public function __toString()
    {
        return $this->timestamp;
        return $this->tablename;
    }
}
