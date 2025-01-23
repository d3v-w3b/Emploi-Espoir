<?php

    namespace App\Form\Types\Users\Employability\OrganizationManager;

    use App\Form\Fields\Users\Employability\OrganizationManager\ApplyForAJobOfferFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ApplyForAJobOfferType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('lastName', TextType::class, [
                    'label' => 'Nom',
                    'attr' => [
                        'readonly' => true,
                    ]
                ])

                ->add('firstName', TextType::class, [
                    'label' => 'Prénoms',
                    'attr' => [
                        'readonly' => true,
                    ]
                ])

                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                    'attr' => [
                        'readonly' => true,
                    ]
                ])

                ->add('phone', TextType::class, [
                    'label' => 'Contact téléphonique',
                ])

                ->add('docsToProvide', FileType::class, [
                    'label' => 'Charger les documents à fournir',
                    'multiple' => true,
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ApplyForAJobOfferFields::class
            ]);
        }
    }