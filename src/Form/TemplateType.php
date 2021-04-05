<?php

namespace App\Form;

use App\Entity\Template;
use App\Entity\TemplateHeader;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['label' => 'Template Name'])
            ->add('header', EntityType::class,['class' => TemplateHeader::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u');},'choice_label' => 'name', 'label' => 'Template Header'])
	    ->add('decommissioned', ChoiceType::class,['choices'  => ['No' => false,'Yes' => true],'label' => 'Decommissioned'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Template::class,
        ]);
    }
}
