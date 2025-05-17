<?php

    namespace App\Form\Types\Users\Profile\Settings;

    use App\Form\Fields\Users\Profile\Settings\UpdatePasswordFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class UpdatePasswordType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('currentPassword', PasswordType::class, [
                    'label' => 'Ancien mot de passe'
                ])

                ->add('newPassword', PasswordType::class, [
                    'label' => 'Nouveau mot de passe'
                ])

                ->add('confirmNewPassword', PasswordType::class, [
                    'label' => 'Réécrire le nouveau mot de passe'
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => UpdatePasswordFields::class
            ]);
        }
    }