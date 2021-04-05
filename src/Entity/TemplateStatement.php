<?php

namespace App\Entity;

use App\Repository\TemplateStatementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="template_statement", 
 *    uniqueConstraints={
 *        @UniqueConstraint(name="unique_row", 
 *            columns={"template_id", "statement_id"})
 *    }
 * )
 * @UniqueEntity(fields={"template", "statement"}, message="Statement is already applied to Template.")
 * @ORM\Entity(repositoryClass=TemplateStatementRepository::class)
 */
class TemplateStatement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="templateStatements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $template;

    /**
     * @ORM\ManyToOne(targetEntity=Statement::class, inversedBy="templateStatements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statement;

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

    public function getStatement(): ?Statement
    {
        return $this->statement;
    }

    public function setStatement(?Statement $statement): self
    {
        $this->statement = $statement;

        return $this;
    }

    public function __toString()
    {
        return $this->template;
        return $this->statement;
    }

}
