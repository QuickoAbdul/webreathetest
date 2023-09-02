<?php

namespace App\Form;

use App\Entity\Module;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

//Formulaire pour créer un nouveau module
class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom du module'
        ])
        ->add('status', TextType::class, [
            'label' => 'Statut du module'
        ])
        ->add('serialNumber', TextType::class, [
            'label' => 'Numéro de série',
            'attr' => [
                'placeholder' => 'xxxx-xxxx-xxxx',
            ],
            'constraints' => [
                new Regex([
                    'pattern' => '/^\d{4}-\d{4}-\d{4}$/',
                    'message' => 'Le numéro de série doit être au format xxxx-xxxx-xxxx.',
                ]),
            ],
        ])
        ->add('status', ChoiceType::class, [
            'label' => 'Statut du module',
            'choices' => [
                'Actif' => 'actif',
                'Inactif' => 'inactif',
            ],
        ])
        ->add('localisation', TextType::class, [
            'label' => 'Localisation'
        ])
        ;
    }

        public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}
