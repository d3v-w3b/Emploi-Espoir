<?php

    namespace App\Form\Types\Users\Account\Career\Language;

    use App\Form\Fields\Users\Account\Career\Language\LanguageLevelFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class LanguageLevelType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('language', ChoiceType::class, [
                    'label' => 'Langue',
                    'choices' => [
                        'Français' => 'Français',
                        'Anglais' => 'Anglais',
                        'Espagnol' => 'Espagnol',
                        'Allemand' => 'Allemand',
                        'Arabe' => 'Arabe'
                    ],
                    'placeholder' => 'Saisissez la langue',
                ])

                ->add('languageLevel', ChoiceType::class, [
                    'label' => 'Niveau de langue',
                    'choices' => [
                        'Avancé' => 'Avancé',
                        'Intermédiaire' => 'Intermédiaire',
                        'Débutant' => 'Débutant'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'required' => false,
                    'placeholder' => false
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => LanguageLevelFields::class
            ]);
        }
    }