<?php

    namespace App\Form\Types\Users\Employability\OrganizationManager;

    use App\Form\Fields\Users\Employability\OrganizationManager\RemoveOfferFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class RemoveOfferType extends AbstractType
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
                'data_class' => RemoveOfferFields::class
            ]);
        }
    }