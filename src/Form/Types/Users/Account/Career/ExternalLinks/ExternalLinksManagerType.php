<?php

    namespace App\Form\Types\Users\Account\Career\ExternalLinks;

    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinksManagerFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ExternalLinksManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('linkType', ChoiceType::class, [
                    'label' => 'Type de lien',
                    'choices' => [
                        'LinkedIn' => 'LinkedIn',
                        'Github' => 'Github',
                        'Autre (site web, portfolio de projet)' => 'Autre'
                    ],
                    'placeholder' => 'Sélectionner le type de lien',
                    'mapped' => false,
                    //'required' => false,
                ])

                ->add('linkedInUrl', TextType::class, [
                    'label' => 'Lien de votre profil LinkedIn',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Format : https://www.linkedin.com/in/votrenom.',
                        'class' => 'external-link input-counter'
                    ]
                ])

                ->add('githubUrl', TextType::class, [
                    'label' => 'Lien de votre profil Github',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Format: “https://github.com/votrepseudo”',
                        'class' => 'external-link input-counter'
                    ]
                ])

                ->add('websiteUrl', TextType::class, [
                    'label' => 'Lien',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Format: “https://www.url.com”',
                        'class' => 'external-link input-counter'
                    ]
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ExternalLinksManagerFields::class,
            ]);
        }
    }