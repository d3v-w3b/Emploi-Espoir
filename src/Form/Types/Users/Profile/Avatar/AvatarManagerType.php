<?php

    namespace App\Form\Types\Users\Profile\Avatar;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\Profile\Avatar\AvatarManagerFields;

    class AvatarManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('inputChoice', ChoiceType::class, [
                    'choices' => [
                        'Photo par défaut' => 'Photo par défaut',
                        'Charger une photo' => 'Charger une photo'
                    ],
                    'expanded' => true,
                    'multiple' => false,
                    'mapped' => false,
                ])

                ->add('profilePic', FileType::class, [
                    'required' => false

                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => AvatarManagerFields::class,
            ]);
        }
    }