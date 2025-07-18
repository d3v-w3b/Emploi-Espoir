<?php

    namespace App\Form\Types\Users\Account\Career\Formation;

    use App\Enum\User\Account\Career\Formation\DiplomaSpeciality;
    use App\Enum\User\Account\Career\Formation\Months;
    use App\Form\Fields\Users\Account\Career\Formation\FormationEditFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EnumType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\HiddenType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class FormationEditType extends AbstractType
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
                        'Autre (certificat)' => 'Autre'
                    ],
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => [
                        'class' => 'diploma-level'
                    ]
                ])

                ->add('diplomaName', TextType::class, [
                    'label' => 'Nom du diplôme',
                    'attr' => [
                        'class' => 'diploma-name-input'
                    ]
                ])

                ->add('diplomaSpeciality', EnumType::class, [
                    'class' => DiplomaSpeciality::class,
                    'choice_label' => fn (DiplomaSpeciality $diplomaSpeciality) => $diplomaSpeciality->getLabel(),
                    'placeholder' => 'Sélectionnez une spécialité',
                    'required' => false,
                    'attr' => [
                        'disabled' => true,
                        'class' => 'diploma-speciality-select-tag'
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
                    'choice_label' => fn (Months $month) => $month->getLabel(),
                ])

                ->add('diplomaYear', ChoiceType::class, [
                    'choices' => array_combine(range(1945, date('Y')), range(1945, date('Y'))),
                    'placeholder' => 'Année',
                ])

                ->add('diploma', FileType::class, [
                    'label' => 'Copie du diplôme ou certificat',
                    'help' => 'Fichier acceptés : .pdf, doc, docx',
                    'multiple' => true,
                    'required' => false,
                    'attr' => [
                        'class' => 'input-file'
                    ]
                ])

                ->add('removed_files', HiddenType::class, [
                    'mapped' => false,
                    'required' => false,
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver):  void
        {
            $resolver->setDefaults([
                'data_class' => FormationEditFields::class,
            ]);
        }
    }