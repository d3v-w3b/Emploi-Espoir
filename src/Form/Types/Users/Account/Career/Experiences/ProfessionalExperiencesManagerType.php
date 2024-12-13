<?php

    namespace App\Form\Types\Users\Account\Career\Experiences;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use App\Form\Fields\Users\Account\Career\Experiences\ProfessionalExperiencesManagerFields;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ProfessionalExperiencesManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('jobTitle', TextType::class, [
                    'label' => 'Intitulé du poste'
                ])

                ->add('jobField', ChoiceType::class, [
                    'choices' => [
                        'Agriculture' => 'agriculture',
                        'Santé' => 'sante',
                        'Éducation' => 'education',
                        'Informatique et TIC' => 'informatique_tic',
                        'Transport et Logistique' => 'transport_logistique',
                        'Construction et Immobilier' => 'construction_immobilier',
                        'Commerce et Distribution' => 'commerce_distribution',
                        'Industrie' => 'industrie',
                        'Finance et Assurance' => 'finance_assurance',
                        'Tourisme et Hôtellerie' => 'tourisme_hotellerie',
                        'Artisanat' => 'artisanat',
                        'Énergie et Mines' => 'énergie_mines',
                        'Communication et Médias' => 'communication_medias',
                        'Administration publique' => 'administration_publique',
                        'Environnement' => 'environnement',
                        'Mode et Beauté' => 'mode_beauté',
                        'Sport et Loisirs' => 'sport_loisirs',
                        'Services à la personne' => 'services_personne',
                        'Recherche et Développement' => 'recherche_développement',
                        'Sécurité et Défense' => 'sécurité_défense',
                        'Justice et Droit' => 'justice_droit',
                        'Culture et Arts' => 'culture_arts',
                    ],
                    'multiple' => true,
                    'attr' => [
                        'disabled' => true,
                    ]


                ])

                ->add('country', ChoiceType::class, [
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
                    //'placeholder' => 'Choisissez un pays',
                    'data' => 'Côte d’Ivoire',
                ])

                ->add('enterpriseName', TextType::class, [
                    'label' => 'Nom de l\'organisation'
                ])

                ->add('endDateCheckbox', ChoiceType::class, [
                    'choices' => [
                        'Je connais la date de fin' => 'Je'
                    ],
                    'expanded' => true,
                    'multiple' => true,
                    'mapped' => false
                ])

                ->add('startDate', DateType::class, [
                    'label' => 'Date de début'
                ])

                ->add('endDate', DateType::class, [
                    'label' => 'Date de fin',
                    'required' => false,
                ])

                ->add('jobDescription', TextareaType::class, [
                    'label' => 'Description du poste',
                    'required' => false
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ProfessionalExperiencesManagerFields::class,
            ]);
        }
    }