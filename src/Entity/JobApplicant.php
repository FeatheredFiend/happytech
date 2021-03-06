<?php

namespace App\Entity;

use App\Repository\JobApplicantRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity(fields={"job", "applicant"}, message="Applicant is already applied to Job.")
 * @ORM\Entity(repositoryClass=JobApplicantRepository::class)
 */
class JobApplicant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Job::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity=Applicant::class, inversedBy="applicants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $applicant;

    /**
     * @ORM\Column(type="boolean")
     */
    private $applicantresponded;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailed;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getApplicant(): ?Applicant
    {
        return $this->applicant;
    }

    public function setApplicant(?Applicant $applicant): self
    {
        $this->applicant = $applicant;

        return $this;
    }

    public function getApplicantresponded(): ?bool
    {
        return $this->applicantresponded;
    }

    public function setApplicantresponded(bool $applicantresponded): self
    {
        $this->applicantresponded = $applicantresponded;

        return $this;
    }

    public function __toString()
    {
        return $this->job;
        return $this->applicant;
        return $this->applicantresponded;
    }

    public function getEmailed(): ?bool
    {
        return $this->emailed;
    }

    public function setEmailed(bool $emailed): self
    {
        $this->emailed = $emailed;

        return $this;
    }

}
