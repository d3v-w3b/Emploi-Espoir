<?php

    namespace App\Form\Types\Public\Home;

    use App\Form\Fields\Public\Home\FilterByTypeOfContractFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class FilterByTypeOfContractType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('typeOfContract', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Stage' => 'Stage',
                    'Alternance' => 'Alternance',
                    'Premier emploi' => 'Premier emploi'
                ],
                'expanded' => true,
                'multiple' => false
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => FilterByTypeOfContractFields::class,
            ]);
        }
    }