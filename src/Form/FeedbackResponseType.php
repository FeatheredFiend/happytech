<?php

namespace App\Form;

use App\Entity\FeedbackResponse;
use App\Entity\Template;
use App\Entity\Job;
use App\Entity\Applicant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class FeedbackResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextType::class,['label' => 'Feedback Comment'])
            ->add('template', EntityType::class,['class' => Template::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Template Name','attr' => ['readonly' => true]])
            ->add('job', EntityType::class,['class' => Job::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Job Name','attr' => ['readonly' => true]])
            ->add('applicant', EntityType::class,['class' => Applicant::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Applicant Name','attr' => ['readonly' => true]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FeedbackResponse::class,
        ]);
    }
}
