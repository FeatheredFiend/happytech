<?php

namespace App\Entity;

use App\Repository\TemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TemplateRepository::class)
 */
class Template
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
     * @ORM\ManyToOne(targetEntity=TemplateHeader::class, inversedBy="templates")
     * @ORM\JoinColumn(nullable=false)
     */
    private $header;

    /**
     * @ORM\OneToMany(targetEntity=TemplateStatement::class, mappedBy="template")
     */
    private $templateStatements;

    /**
     * @ORM\OneToMany(targetEntity=FeedbackResponse::class, mappedBy="template")
     */
    private $feedbackResponses;

    /**
     * @ORM\Column(type="boolean")
     */
    private $decommissioned;

    public function __construct()
    {
        $this->templateStatements = new ArrayCollection();
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

    public function getHeader(): ?TemplateHeader
    {
        return $this->header;
    }

    public function setHeader(?TemplateHeader $header): self
    {
        $this->header = $header;

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
            $templateStatement->setTemplate($this);
        }

        return $this;
    }

    public function removeTemplateStatement(TemplateStatement $templateStatement): self
    {
        if ($this->templateStatements->removeElement($templateStatement)) {
            // set the owning side to null (unless already changed)
            if ($templateStatement->getTemplate() === $this) {
                $templateStatement->setTemplate(null);
            }
        }

        return $this;
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
            $feedbackResponse->setTemplate($this);
        }

        return $this;
    }

    public function removeFeedbackResponse(FeedbackResponse $feedbackResponse): self
    {
        if ($this->feedbackResponses->removeElement($feedbackResponse)) {
            // set the owning side to null (unless already changed)
            if ($feedbackResponse->getTemplate() === $this) {
                $feedbackResponse->setTemplate(null);
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
