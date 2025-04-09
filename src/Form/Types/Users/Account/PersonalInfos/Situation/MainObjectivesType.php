<?php

    namespace App\Form\Types\Users\Account\PersonalInfos\Situation;

    use App\Form\Fields\Users\Account\PersonalInfos\Situation\MainObjectivesFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class MainObjectivesType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('mainObjectives', ChoiceType::class, [
                'label' => 'Que recherchez-vous ?',
                'choices' => [
                    'Alternance' => 'Alternance',
                    'Premier emploi' => 'Premier emploi'
                ],
                'expanded' => true,
                'multiple' => true
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => MainObjectivesFields::class
            ]);
        }
    }