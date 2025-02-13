<?php

    namespace App\Form\Types\Users\Account\PersonalInfos\Address;

    use App\Form\Fields\Users\Account\PersonalInfos\Address\AddressManagerFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class AddressManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => [
                    'placeholder' => 'EX : Cocody - Abidjan'
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => AddressManagerFields::class
            ]);
        }
    }