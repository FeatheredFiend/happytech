<?php

namespace App\Entity;

use App\Repository\ApplicantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ApplicantRepository::class)
 * @UniqueEntity(fields="name", message="Name is already taken.")
 * @UniqueEntity(fields="email", message="Email is already taken.")
 */
class Applicant
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=JobApplicant::class, mappedBy="applicant")
     */
    private $applicants;

    /**
     * @ORM\OneToMany(targetEntity=FeedbackResponse::class, mappedBy="applicant")
     */
    private $feedbackResponses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $decommissioned;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cv;

    public function __construct()
    {
        $this->applicants = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|JobApplicant[]
     */
    public function getApplicants(): Collection
    {
        return $this->applicants;
    }

    public function addApplicant(JobApplicant $applicant): self
    {
        if (!$this->applicants->contains($applicant)) {
            $this->applicants[] = $applicant;
            $applicant->setApplicant($this);
        }

        return $this;
    }

    public function removeApplicant(JobApplicant $applicant): self
    {
        if ($this->applicants->removeElement($applicant)) {
            // set the owning side to null (unless already changed)
            if ($applicant->getApplicant() === $this) {
                $applicant->setApplicant(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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
            $feedbackResponse->setApplicant($this);
        }

        return $this;
    }

    public function removeFeedbackResponse(FeedbackResponse $feedbackResponse): self
    {
        if ($this->feedbackResponses->removeElement($feedbackResponse)) {
            // set the owning side to null (unless already changed)
            if ($feedbackResponse->getApplicant() === $this) {
                $feedbackResponse->setApplicant(null);
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

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }
}
