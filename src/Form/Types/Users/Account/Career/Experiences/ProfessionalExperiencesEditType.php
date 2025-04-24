<?php

    namespace App\Form\Types\Users\Account\Career\Experiences;

    use App\Form\Fields\Users\Account\Career\Experiences\ProfessionalExperiencesEditFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ProfessionalExperiencesEditType extends AbstractType
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

                ->add('town', ChoiceType::class, [
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
                    ]
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
                    'help' => 'Présenter brièvement vos missions, responsabilités, résultats obtenus, projet et outils.',
                    'required' => false,
                    'attr' => [
                        'cols' => 60,
                        'rows' => 6,
                        'max' => 1000
                    ]
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ProfessionalExperiencesEditFields::class,
            ]);
        }
    }