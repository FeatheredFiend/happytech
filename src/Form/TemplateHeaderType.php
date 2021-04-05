<?php

namespace App\Form;

use App\Entity\TemplateHeader;
use App\Entity\User;
use App\Entity\FeedbackType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;

class TemplateHeaderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,['label' => 'Template Header Name'])
            ->add('user', EntityType::class,['class' => User::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'User Name'])
            ->add('feedbacktype', EntityType::class,['class' => FeedbackType::class, 'query_builder' => function (EntityRepository $er) {return $er->createQueryBuilder('u')->andWhere('u.decommissioned = 0');}, 'choice_label' => 'name', 'label' => 'Feedback Type'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplateHeader::class,
        ]);
    }
}
