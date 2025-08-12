<?php

    namespace App\Form\Types\Users\Employability\OrganizationManager;

    use App\Form\Fields\Users\Employability\OrganizationManager\HiringFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class HiringType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('organizationResponse', TextareaType::class, [
                    'label' => 'Votre réponse',
                    'help' => 'Le candidat recevra votre message ainsi que vos coordonnées par e-mail.',
                    'attr' => [
                        'cols' => 80,
                        'rows' => 8
                    ]
                ])

                ->add('orgOwnerFirstName', TextType::class, [
                    'label' => 'Votre prénom'
                ])

                ->add('orgOwnerLastName', TextType::class, [
                    'label' => 'Votre nom'
                ])

                ->add('orgOwnerEmail', EmailType::class, [
                    'label' => 'Votre e-mail'
                ])

                ->add('orgOwnerPhone', TextType::class, [
                    'label' => 'Votre téléphone',
                    'required' => false
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => HiringFields::class
            ]);
        }
    }