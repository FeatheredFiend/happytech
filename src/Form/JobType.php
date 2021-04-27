<?php

namespace App\Form;

use App\Entity\JobCategory;
use App\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;


class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['label' => 'Job Name'])
            ->add('description', TextareaType::class,['label' => 'Job Description'])
            ->add('jobcategory', EntityType::class,['class' => JobCategory::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Job Category'])
            ->add('duedate', DateType::class,['label' => 'Job End Date'])
	    ->add('decommissioned', ChoiceType::class,['choices'  => ['No' => false,'Yes' => true],'label' => 'Decommissioned'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
