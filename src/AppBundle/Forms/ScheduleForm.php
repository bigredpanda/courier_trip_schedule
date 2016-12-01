<?php

namespace AppBundle\Forms;


use AppBundle\Entity\Courier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('region', EntityType::class, [
            'class'        => 'AppBundle:Region',
            'choice_label' => 'name',
        ])
            ->add('courier', EntityType::class, [
                'class'        => 'AppBundle:Courier',
                'choice_label' => function ($courier) {
                    /** @var Courier $courier */
                    return $courier->getLastName() . ' ' . $courier->getFirstName() .
                    ' ' . $courier->getMiddleName();
                },
            ])
            ->add('departureDate', DateType::class, [
                'widget'   => 'single_text',
                'html5' => false

            ])
            ->add('arrivalDate', DateType::class, [
                'widget'   => 'single_text',
                'html5' => false,
                'attr' => [
                    'readonly' => true
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Schedule',
        ]);
    }
}