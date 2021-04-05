<?php

namespace App\Entity;

use App\Repository\FeedbackTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=FeedbackTypeRepository::class)
 * @UniqueEntity(fields="name", message="Feedback Type is already taken.")
 */
class FeedbackType
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
     * @ORM\OneToMany(targetEntity=TemplateHeader::class, mappedBy="feedbacktype")
     */
    private $templateHeaders;

    /**
     * @ORM\Column(type="boolean")
     */
    private $decommissioned;

    public function __construct()
    {
        $this->templateHeaders = new ArrayCollection();
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
     * @return Collection|TemplateHeader[]
     */
    public function getTemplateHeaders(): Collection
    {
        return $this->templateHeaders;
    }

    public function addTemplateHeader(TemplateHeader $templateHeader): self
    {
        if (!$this->templateHeaders->contains($templateHeader)) {
            $this->templateHeaders[] = $templateHeader;
            $templateHeader->setFeedbacktype($this);
        }

        return $this;
    }

    public function removeTemplateHeader(TemplateHeader $templateHeader): self
    {
        if ($this->templateHeaders->removeElement($templateHeader)) {
            // set the owning side to null (unless already changed)
            if ($templateHeader->getFeedbacktype() === $this) {
                $templateHeader->setFeedbacktype(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
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
