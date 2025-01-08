<?php

    namespace App\Form\Types\Users\UserPanel;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\UserPanel\MainObjectivesFields;

    class MainObjectivesType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('mainObjectives', ChoiceType::class, [
                'choices' => [
                    'Trouver une alternance' => 'Trouver une alternance',
                    'Trouver un premier emploi' => 'Trouver un premier emploi',
                ],
                'expanded' => true,
                'multiple' => true
            ]);
            
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => MainObjectivesFields::class,
            ]);
        }
    }