<?php

namespace App\Entity;

use App\Repository\FeedbackResponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="feedback_response", 
 *    uniqueConstraints={
 *        @UniqueConstraint(name="unique_row", 
 *            columns={"template_id", "applicant_id", "job_id"})
 *    }
 * )
 *
 * @ORM\Entity(repositoryClass=FeedbackResponseRepository::class)
 */
class FeedbackResponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="feedbackResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $template;

    /**
     * @ORM\ManyToOne(targetEntity=Applicant::class, inversedBy="feedbackResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $applicant;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="feedbackResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\OneToMany(targetEntity=FeedbackResponseStatement::class, mappedBy="feedbackresponse")
     */
    private $feedbackResponseStatements;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $feedback;

    public function __construct()
    {
        $this->feedbackResponseStatements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getApplicant(): ?Applicant
    {
        return $this->applicant;
    }

    public function setApplicant(?Applicant $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function __toString()
    {
        return $this->template;
        return $this->applicant;
        return $this->job;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
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
            $feedbackResponseStatement->setFeedbackresponse($this);
        }

        return $this;
    }

    public function removeFeedbackResponseStatement(FeedbackResponseStatement $feedbackResponseStatement): self
    {
        if ($this->feedbackResponseStatements->removeElement($feedbackResponseStatement)) {
            // set the owning side to null (unless already changed)
            if ($feedbackResponseStatement->getFeedbackresponse() === $this) {
                $feedbackResponseStatement->setFeedbackresponse(null);
            }
        }

        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

}
