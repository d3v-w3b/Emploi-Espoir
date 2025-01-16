<?php

    namespace App\Form\Types\Users\Employability\OrganizationManager;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\CollectionType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\Employability\OrganizationManager\AddJobOfferFields;

    class AddJobOfferType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('jobTitle', TextType::class, [
                    'label' => 'Intitulé du poste'
                ])

                ->add('typeOfContract', ChoiceType::class, [
                    'label' => 'Type de contract',
                    'choices' => [
                        'CDI' => 'CDI',
                        'CDD' => 'CDD',
                        'Stage' => 'Stage',
                        'Alternance' => 'Alternance'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                ])

                ->add('town', TextType::class, [
                    'label' => 'Lieu de travail'
                ])

                ->add('jobPreferences', ChoiceType::class, [
                    'label' => 'Préférence de l\'emploi',
                    'choices' =>  [
                        'Présentiel uniquement' => 'Présentiel uniquement',
                        'Télétravail partiel' => 'Télétravail partiel',
                        'Télétravail uniquement' => 'Télétravail uniquement'
                    ],
                    'expanded' => true,
                    'multiple' => false
                ])

                ->add('organizationAbout', TextareaType::class, [
                    'label' => 'Qui sommes-nous ?',
                    'attr' => [
                        'max' => 400
                    ]
                ])

                ->add('missions', TextType::class, [
                    'label' => 'Missions du poste',
                    //'entry_type' => TextType::class,        // Chaque mission est un champ de texte
                ])

                ->add('profilSought', TextType::class, [
                    'label' => 'Profils recherchés'
                ])

                ->add('whatWeOffer', TextType::class, [
                    'label' => 'Ce que nous offrons',
                    'required' => false,
                ])

                ->add('docsToProvide', ChoiceType::class, [
                    'label' => 'Documents à fournir',
                    'choices' => [
                        'CV' => 'CV',
                        'Lettre de motivation' => 'Lettre de motivation',
                        'Portfolio' => 'Portfolio'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                ])

                ->add('expirationDate', DateType::class, [
                    'label' => 'Date d\'expiration de l\'offres'
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => AddJobOfferFields::class,
            ]);
        }
    }