<?php

    namespace App\Form\Types\Users\RegisterAndAuth;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\RegisterAndAuth\SaveUserFields;

    class SaveUserTypes extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'E-mail',
                    'help' => 'Vous recevrez votre mot de passe sur cet adresse e-mail',
                    'attr' => [
                        'readonly' => true,
                    ]
                ])

                ->add('lastName', TextType::class, [
                    'label' => 'Prénom'
                ])

                ->add('firstName', TextType::class, [
                    'label' => 'Nom'
                ])

                ->add('dateOfBirth', DateType::class, [
                    'label' => 'Date de naissance',
                    'input' => 'datetime_immutable',
                    'widget' => 'single_text',
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => SaveUserFields::class,
            ]);
        }
    }