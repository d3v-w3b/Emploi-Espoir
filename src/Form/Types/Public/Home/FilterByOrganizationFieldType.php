<?php

    namespace App\Form\Types\Public\Home;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Public\Home\FilterByOrganizationFieldFields;

    class FilterByOrganizationFieldType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('organizationField', ChoiceType::class, [
                'choices' => [
                    'Agriculture' => 'Agriculture',
                    'Commerce' => 'Commerce',
                    'Informatique' => 'Informatique',
                    'Communication' => 'Communication',
                    'Finance' => 'Banque',
                    'Santé' => 'Santé',
                    'Éducation' => 'Éducation',
                    'BTP' => 'BTP',
                    'Transport' => 'Transport',
                    'Énergie' => 'Énergie',
                    'Tourisme' => 'Tourisme',
                    'Textile' => 'Textile',
                    'Artisanat' => 'Artisanat',
                    'Industrie' => 'Industrie',
                    'Immobilier' => 'Immobilier',
                    'Culture' => 'Arts',
                    'Environnement' => 'Environnement',
                    'Services' => 'Services',
                ],
                'expanded' => true,
                'multiple' => false
            ]);
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => FilterByOrganizationFieldFields::class,
            ]);
        }
    }