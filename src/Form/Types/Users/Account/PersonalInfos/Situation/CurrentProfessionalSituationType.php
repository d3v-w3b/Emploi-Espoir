<?php

    namespace App\Form\Types\Users\Account\PersonalInfos\Situation;

    use App\Form\Fields\Users\Account\PersonalInfos\Situation\CurrentProfessionalSituationFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CurrentProfessionalSituationType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('currentProfessionalSituation', ChoiceType::class, [
                'label' => 'Situation professionnelle actuelle',
                'choices' => [
                    'Étudiant' => 'Étudiant',
                    'Démandeur d\'emploi' => 'Démandeur d\'emploi',
                    'En recherche d\'alternance' => 'En recherche d\'alternance'
                ],
                'placeholder' => 'Sélectionnez une situation professionnelle',
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => CurrentProfessionalSituationFields::class
            ]);
        }
    }