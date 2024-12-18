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
                ->add('country', ChoiceType::class, [
                    'label' => 'Pays',
                    'choices' => [
                        'Afrique du Sud' => 'Afrique du Sud',
                        'Algérie' => 'Algérie',
                        'Côte d’Ivoire' => 'Côte d’Ivoire',
                        'Égypte' => 'Égypte',
                        'Ghana' => 'Ghana',
                        'Kenya' => 'Kenya',
                        'Maroc' => 'Maroc',
                        'Nigeria' => 'Nigeria',
                        'Argentine' => 'Argentine',
                        'Brésil' => 'Brésil',
                        'Canada' => 'Canada',
                        'Chili' => 'Chili',
                        'Colombie' => 'Colombie',
                        'États-Unis' => 'USA',
                        'Mexique' => 'Mexique',
                        'Pérou' => 'Pérou',
                        'Arabie Saoudite' => 'Arabie Saoudite',
                        'Chine' => 'Chine',
                        'Corée du Sud' => 'Corée du Sud',
                        'Inde' => 'Inde',
                        'Indonésie' => 'Indonésie',
                        'Japon' => 'Japon',
                        'Thaïlande' => 'Thaïlande',
                        'Vietnam' => 'Vietnam',
                        'Allemagne' => 'Allemagne',
                        'Espagne' => 'Espagne',
                        'France' => 'France',
                        'Italie' => 'Italie',
                        'Pays-Bas' => 'Pays-Bas',
                        'Pologne' => 'Pologne',
                        'Royaume-Uni' => 'Royaume-Uni',
                        'Suisse' => 'Suisse',
                    ],
                    'data' => 'Côte d’Ivoire',
                ])

                ->add('organizationName', TextType::class, [
                    'label' => 'Nom de l\'organisation'
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