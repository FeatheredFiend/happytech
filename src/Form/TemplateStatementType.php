<?php

namespace App\Form;

use App\Entity\TemplateStatement;
use App\Entity\Template;
use App\Entity\Statement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;


class TemplateStatementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('template', EntityType::class,['class' => Template::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Template'])
            ->add('statement', EntityType::class,['class' => Statement::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'statement', 'label' => 'Statement'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplateStatement::class,
        ]);
    }
}
