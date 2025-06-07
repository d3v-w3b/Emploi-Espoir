<?php

    namespace App\Form\Types\Admin\RegisterAndAuth;

    use App\Form\Fields\Admin\RegisterAndAuth\AdminRegisterFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class AdminRegisterType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail'
                ])

                ->add('adminName', TextType::class, [
                    'label' => 'Nom d\'administrateur (pseudonyme)'
                ])

                ->add('password', PasswordType::class, [
                    'label' => 'Créer un mot de passe'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => AdminRegisterFields::class,
            ]);
        }
    }