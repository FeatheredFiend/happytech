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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class FeedbackResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextType::class,['label' => 'Feedback Comment'])
            ->add('template', EntityType::class,['class' => Template::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Template Name'])
            ->add('job', EntityType::class,['class' => Job::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Job Name'])
            ->add('applicant', EntityType::class,['class' => Applicant::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Applicant Name'])
            ->add('feedback', FileType::class, [
                'label' => 'PDF Feedback (PDF file)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FeedbackResponse::class,
        ]);
    }
}
