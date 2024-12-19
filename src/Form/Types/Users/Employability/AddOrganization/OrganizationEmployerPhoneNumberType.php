<?php

    namespace App\Form\Types\Users\Employability\AddOrganization;

    use App\Form\Fields\Users\Employability\AddOrganization\OrganizationEmployerPhoneNumberFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class OrganizationEmployerPhoneNumberType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('phone', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Ex : 0502304963'
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => OrganizationEmployerPhoneNumberFields::class,
            ]);
        }
    }