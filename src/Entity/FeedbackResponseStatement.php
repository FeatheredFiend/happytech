<?php

namespace App\Entity;

use App\Repository\FeedbackResponseStatementRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @ORM\Entity
 * @ORM\Table(name="feedback_response_statement", 
 *    uniqueConstraints={
 *        @UniqueConstraint(name="unique_row", 
 *            columns={"feedbackresponse_id", "statement_id"})
 *    }
 * )
 * 
 * @ORM\Entity(repositoryClass=FeedbackResponseStatementRepository::class)
 */
class FeedbackResponseStatement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=FeedbackResponse::class, inversedBy="feedbackResponseStatements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $feedbackresponse;

    /**
     * @ORM\ManyToOne(targetEntity=Statement::class, inversedBy="feedbackResponseStatements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedbackresponse(): ?FeedbackResponse
    {
        return $this->feedbackresponse;
    }

    public function setFeedbackresponse(?FeedbackResponse $feedbackresponse): self
    {
        $this->feedbackresponse = $feedbackresponse;

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

}
