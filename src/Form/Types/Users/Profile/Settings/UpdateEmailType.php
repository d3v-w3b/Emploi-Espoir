<?php

    namespace App\Form\Types\Users\Profile\Settings;

    use App\Form\Fields\Users\Profile\Settings\UpdateEmailFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UpdateEmailType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentEmail', EmailType::class, [
                    'label' => 'Adresse e-mail actuel',
                    'attr' => [
                        'readonly' => true
                    ]
                ])

                ->add('newEmail', EmailType::class, [
                    'label' => 'Nouvelle e-mail'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => UpdateEmailFields::class
            ]);
        }
    }