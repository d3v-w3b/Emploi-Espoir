<?php

    namespace App\Form\Types\Users\Profile\Settings;

    use App\Form\Fields\Users\Profile\Settings\DeleteUserAccountFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class DeleteUserAccountType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('password', PasswordType::class, [
                'label' => 'Mot de passe'
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => DeleteUserAccountFields::class
            ]);
        }
    }