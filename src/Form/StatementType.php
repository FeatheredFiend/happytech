<?php

namespace App\Form;

use App\Entity\Statement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class StatementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('statement', TextType::class,['label' => 'Statement'])
	    ->add('decommissioned', ChoiceType::class,['choices'  => ['No' => false,'Yes' => true],'label' => 'Decommissioned'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Statement::class,
        ]);
    }
}
