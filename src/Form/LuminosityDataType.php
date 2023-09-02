<?php

namespace App\Form;

use App\Entity\LuminosityData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Formulaire pour créer de nouvelle donnée de luminosité
class LuminosityDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('value', IntegerType::class, [
                'label' => 'Valeur de luminosité',
            ])
            ->add('timestamp', DateTimeType::class, [
                'label' => 'Date et heure',
                'widget' => 'single_text', // Affiche un champ de date et d'heure en texte
                'format' => 'dd/MM/yyyy HH:mm:ss', // Format personnalisé
                'html5' => false, // Désactive le type HTML5 pour ce champ
                'attr' => [
                    'placeholder' => 'dd/MM/yyyy HH:mm:ss',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LuminosityData::class,
        ]);
    }
}
