<?php

namespace App\Entity;

use App\Repository\StatementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=StatementRepository::class)
 * @UniqueEntity(fields="statement", message="Statement is already taken.")
 */
class Statement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $statement;

    /**
     * @ORM\OneToMany(targetEntity=TemplateStatement::class, mappedBy="statement")
     */
    private $templateStatements;

    /**
     * @ORM\OneToMany(targetEntity=FeedbackResponseStatement::class, mappedBy="statement")
     */
    private $feedbackResponseStatements;

    /**
     * @ORM\Column(type="boolean")
     */
    private $decommissioned;

    public function __construct()
    {
        $this->templateStatements = new ArrayCollection();
        $this->feedbackResponseStatements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatement(): ?string
    {
        return $this->statement;
    }

    public function setStatement(string $statement): self
    {
        $this->statement = $statement;

        return $this;
    }

    /**
     * @return Collection|TemplateStatement[]
     */
    public function getTemplateStatements(): Collection
    {
        return $this->templateStatements;
    }

    public function addTemplateStatement(TemplateStatement $templateStatement): self
    {
        if (!$this->templateStatements->contains($templateStatement)) {
            $this->templateStatements[] = $templateStatement;
            $templateStatement->setStatement($this);
        }

        return $this;
    }

    public function removeTemplateStatement(TemplateStatement $templateStatement): self
    {
        if ($this->templateStatements->removeElement($templateStatement)) {
            // set the owning side to null (unless already changed)
            if ($templateStatement->getStatement() === $this) {
                $templateStatement->setStatement(null);
            }
        }

        return $this;
    }

    public function __toString()
    {

        return $this->statement;

    }

    /**
     * @return Collection|FeedbackResponseStatement[]
     */
    public function getFeedbackResponseStatements(): Collection
    {
        return $this->feedbackResponseStatements;
    }

    public function addFeedbackResponseStatement(FeedbackResponseStatement $feedbackResponseStatement): self
    {
        if (!$this->feedbackResponseStatements->contains($feedbackResponseStatement)) {
            $this->feedbackResponseStatements[] = $feedbackResponseStatement;
            $feedbackResponseStatement->setStatement($this);
        }

        return $this;
    }

    public function removeFeedbackResponseStatement(FeedbackResponseStatement $feedbackResponseStatement): self
    {
        if ($this->feedbackResponseStatements->removeElement($feedbackResponseStatement)) {
            // set the owning side to null (unless already changed)
            if ($feedbackResponseStatement->getStatement() === $this) {
                $feedbackResponseStatement->setStatement(null);
            }
        }

        return $this;
    }

    public function getDecommissioned(): ?bool
    {
        return $this->decommissioned;
    }

    public function setDecommissioned(bool $decommissioned): self
    {
        $this->decommissioned = $decommissioned;

        return $this;
    }
}
