<?php

    namespace App\Form\Types\Users\Account\PersonalInfos\Identity;

    use App\Form\Fields\Users\Account\PersonalInfos\Identity\DateOfBirthEditFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class DateOfBirthEditType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('dateOfBirth', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'choice',
                'format' => 'dd MM yyyy',
                'years' => range(date('Y'), 1950),
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => DateOfBirthEditFields::class
            ]);
        }
    }