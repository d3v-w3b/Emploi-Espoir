<?php

    namespace App\Form\Types\Admin\OrganizationManager\RemovalRequest;

    use App\Form\Fields\Admin\OrganizationManager\RemovalRequest\OrganizationRemovedRequestFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class OrganizationRemovedRequestType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('password', PasswordType::class, [
                'label' => 'Mot de passe administrateur'
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => OrganizationRemovedRequestFields::class,
            ]);
        }
    }