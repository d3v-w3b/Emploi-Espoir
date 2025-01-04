<?php

    namespace App\Form\Types\Users\Employability\AddOrganization;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class OrganizationBasicsInfosType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('town', ChoiceType::class, [
                    'label' => 'Ville',
                    'choices' => [
                        'Abidjan' => 'Abidjan',
                        'Bouaké' => 'Bouaké',
                        'Daloa' => 'Daloa',
                        'San-Pédro' => 'San-Pédro',
                        'Yamoussoukro' => 'Yamoussoukro',
                        'Korhogo' => 'Korhogo',
                        'Man' => 'Man',
                        'Gagnoa' => 'Gagnoa',
                        'Divo' => 'Divo',
                        'Abengourou' => 'Abengourou',
                        'Soubré' => 'Soubré',
                        'Anyama' => 'Anyama',
                        'Bondoukou' => 'Bondoukou',
                        'Séguéla' => 'Séguéla',
                        'Odienné' => 'Odienné',
                        'Toumodi' => 'Toumodi',
                        'Ferkessédougou' => 'Ferkessédougou',
                        'Issia' => 'Issia',
                        'Sinfra' => 'Sinfra',
                        'Grand-Bassam' => 'Grand-Bassam',
                        'Adzopé' => 'Adzopé',
                        'Agnibilékrou' => 'Agnibilékrou',
                        'Bingerville' => 'Bingerville',
                        'Tiassalé' => 'Tiassalé',
                        'Tabou' => 'Tabou',
                        'Daoukro' => 'Daoukro',
                        'Boundiali' => 'Boundiali',
                        'Zuenoula' => 'Zuenoula',
                        'Méagui' => 'Méagui',
                        'Tengrela' => 'Tengrela',
                        'Sassandra' => 'Sassandra',
                    ],
                    'data' => 'Abidjan',
                ])

                ->add('organizationName', TextType::class, [
                    'label' => 'Nom de l\'organisation'
                ])

                ->add('organizationPreferences', ChoiceType::class, [
                    'label' => 'Préférences de l\'entreprise',
                    'choices' => [
                        'Trouver un premier emploi' => 'Trouver un premier emploi',
                        'Trouver une alternance' => 'Trouver une alternance'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'required' => true
                ])

                ->add('organizationRegistrationNumber', TextType::class, [
                    'label' => 'Matricule Fiscal',
                    'attr' => [
                        'placeholder' => 'EX : CI-123456789-R'
                    ]
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => \App\Form\Fields\Users\Employability\AddOrganization\OrganizationBasicsInfosFields::class,
            ]);
        }
    }