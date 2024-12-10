<?php

    namespace App\Form\Types\Users\Account\Career\Formation;

    use App\Enum\User\Account\Career\Formation\DiplomaSpeciality;
    use App\Enum\User\Account\Career\Formation\Months;
    use Symfony\Component\Form\AbstractType;
    use App\Form\Fields\Users\Account\Career\Formation\FormationManagerFields;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EnumType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class FormationManagerTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('diplomaLevel', ChoiceType::class, [
                    'label' => 'Niveau du diplôme',
                    'choices' => [
                        'Aucun diplôme' => 'Aucun diplôme',
                        'Bac' => 'Bac',
                        'Bac +2' => 'Bac +2',
                        'Bac +3/4' => 'Bac +3/4',
                        'Bac +5' => 'Bac +5',
                        'Doctorat' => 'Doctorat',
                        'Autre' => 'Autre (certificat)'
                    ],
                    'expanded' => false,
                    'multiple' => false
                ])

                ->add('diplomaName', TextType::class, [
                    'label' => 'Nom du diplôme'
                ])

                ->add('diplomaSpecialities', EnumType::class, [
                    'class' => DiplomaSpeciality::class,
                    'choice_label' => 'value',
                    'attr' => [
                        'placeholder' => 'Sélectionner la ou les spécialités du diplôme',
                    ]
                ])

                ->add('universityName', TextType::class, [
                    'label' => 'Nom de l\'université l\'école ou l\'organisme de formation',
                ])

                ->add('diplomaTown', TextType::class, [
                    'label' => 'Ville d\'obtention du diplôme',
                ])

                ->add('diplomaMonth', EnumType::class, [
                    'class' => Months::class,
                    'choice_label' => 'value'
                ])

                ->add('diplomaYear', ChoiceType::class, [
                    'choices' => array_combine(range(1945, date('Y')), range(1945, date('Y'))),
                    'attr' => [
                        'placeholder' => 'Année',
                    ]
                ])

                ->add('diploma', FileType::class, [
                    'label' => 'Copie du diplôme ou certificat',
                    'multiple' => true
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => FormationManagerFields::class,
            ]);
        }
    }