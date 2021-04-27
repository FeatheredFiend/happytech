<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $duedate;

    /**
     * @ORM\OneToMany(targetEntity=JobApplicant::class, mappedBy="job")
     */
    private $jobs;

    /**
     * @ORM\ManyToOne(targetEntity=JobCategory::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $jobcategory;

    /**
     * @ORM\OneToMany(targetEntity=FeedbackResponse::class, mappedBy="job")
     */
    private $feedbackResponses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $decommissioned;

    public function __construct()
    {
        $this->jobs = new ArrayCollection();
        $this->feedbackResponses = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDuedate(): ?\DateTimeInterface
    {
        return $this->duedate;
    }

    public function setDuedate(\DateTimeInterface $duedate): self
    {
        $this->duedate = $duedate;

        return $this;
    }

    /**
     * @return Collection|JobApplicant[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(JobApplicant $job): self
    {
        if (!$this->jobs->contains($job)) {
            $this->jobs[] = $job;
            $job->setJob($this);
        }

        return $this;
    }

    public function removeJob(JobApplicant $job): self
    {
        if ($this->jobs->removeElement($job)) {
            // set the owning side to null (unless already changed)
            if ($job->getJob() === $this) {
                $job->setJob(null);
            }
        }

        return $this;
    }

    public function getJobcategory(): ?JobCategory
    {
        return $this->jobcategory;
    }

    public function setJobcategory(?JobCategory $jobcategory): self
    {
        $this->jobcategory = $jobcategory;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
        return $this->jobcategory;
    }

    /**
     * @return Collection|FeedbackResponse[]
     */
    public function getFeedbackResponses(): Collection
    {
        return $this->feedbackResponses;
    }

    public function addFeedbackResponse(FeedbackResponse $feedbackResponse): self
    {
        if (!$this->feedbackResponses->contains($feedbackResponse)) {
            $this->feedbackResponses[] = $feedbackResponse;
            $feedbackResponse->setJobid($this);
        }

        return $this;
    }

    public function removeFeedbackResponse(FeedbackResponse $feedbackResponse): self
    {
        if ($this->feedbackResponses->removeElement($feedbackResponse)) {
            // set the owning side to null (unless already changed)
            if ($feedbackResponse->getJobid() === $this) {
                $feedbackResponse->setJobid(null);
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
