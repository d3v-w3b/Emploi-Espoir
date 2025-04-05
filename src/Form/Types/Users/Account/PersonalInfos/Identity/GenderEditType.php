<?php

    namespace App\Form\Types\Users\Account\PersonalInfos\Identity;

    use App\Form\Fields\Users\Account\PersonalInfos\Identity\GenderEditFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class GenderEditType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('gender', ChoiceType::class, [
                'choices' => [
                    'Féminin' => 'Féminin',
                    'Masculin' => 'Masculin',
                    'Je préfère ne pas répondre' => 'Je préfère ne pas répondre'
                ],
                'expanded' => true,
                'multiple' => false
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => GenderEditFields::class
            ]);
        }
    }