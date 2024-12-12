<?php

    namespace App\Form\Types\Users\Account\Career\Skills;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\Account\Career\Skills\SkillsManagerFields;

    class SkillsManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('skills', ChoiceType::class, [
                'choices' => [
                    'Développement Web' => 'Développement Web',
                    'Administration Réseau' => 'Administration Réseau',
                    'Gestion de Projet' => 'Gestion de Projet',
                    'Design Graphique' => 'Design Graphique',
                    'Production Vidéo' => 'Production Vidéo',
                    'Agriculture Moderne' => 'Agriculture Moderne',
                    'Commerce en Ligne' => 'Commerce en Ligne',
                    'Comptabilité et Gestion' => 'Comptabilité et Gestion',
                    'Marketing Digital' => 'Marketing Digital',
                    'Soudure et Métallurgie' => 'Soudure et Métallurgie',
                    'Énergies Renouvelables' => 'Énergies Renouvelables',
                    'Développement Mobile' => 'Développement Mobile',
                    'Analyse de Données' => 'Analyse de Données',
                    'Service Client' => 'Service Client',
                    'Enseignement et Formation' => 'Enseignement et Formation',
                    'Programmation Python' => 'Programmation Python',
                    'Maintenance Informatique' => 'Maintenance Informatique',
                    'Cuisine Professionnelle' => 'Cuisine Professionnelle',
                    'Entrepreneuriat' => 'Entrepreneuriat',
                    'Journalisme et Communication' => 'Journalisme et Communication',
                    'Création d’Applications' => 'Création d’Applications',
                    'Gestion des Ressources Humaines' => 'Gestion des Ressources Humaines',
                    'Traduction et Interprétation' => 'Traduction et Interprétation',
                    'Arts Visuels et Peinture' => 'Arts Visuels et Peinture',
                    'Gestion Logistique' => 'Gestion Logistique',
                    'Fabrication Artisanale' => 'Fabrication Artisanale',
                    'Construction et BTP' => 'Construction et BTP',
                ],
                'multiple' => true,
                'attr' => [
                    'size' => 10,
                    'class' => 'js-example-basic-multiple'
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => SkillsManagerFields::class,
            ]);
        }
    }