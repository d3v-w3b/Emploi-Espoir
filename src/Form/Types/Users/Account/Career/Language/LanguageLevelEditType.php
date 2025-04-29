<?php

    namespace App\Form\Types\Users\Account\Career\Language;

    use App\Form\Fields\Users\Account\Career\Language\LanguageLevelEditFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class LanguageLevelEditType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('languageLevel', ChoiceType::class, [
                'choices' => [
                    'Avancé' => 'Avancé',
                    'Intermédiaire' => 'Intermédiaire',
                    'Débutant' => 'Débutant'
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => LanguageLevelEditFields::class
            ]);
        }
    }