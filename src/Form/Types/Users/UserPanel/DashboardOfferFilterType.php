<?php

    namespace App\Form\Types\Users\UserPanel;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class DashboardOfferFilterType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('typeOfContract', ChoiceType::class, [
                    'choices' => [
                        'CDI' => 'CDI',
                        'CDD' => 'CDD',
                        'Stage' => 'Stage',
                        'Alternance' => 'Alternance',
                        'Premier emploi' => 'Premier emploi'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'required' => false,
                    'placeholder' => 'Tous les types'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([]);
        }
    }