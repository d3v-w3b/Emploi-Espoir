<?php

    namespace App\Form\Types\Public\HelpCenter;

    use App\Form\Fields\Public\HelpCenter\HelpCenterFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class HelpCenterType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('lastName', TextType::class, [
                    'label' => 'Nom'
                ])

                ->add('firstName', TextType::class, [
                    'label' => 'Prénom'
                ])

                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail'
                ])

                ->add('phone', TextType::class, [
                    'label' => 'Contact téléphonique',
                    'required' => false
                ])

                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'attr' => [
                        'placeholder' => 'Décrivez explicitement le problème que vous rencontrez'
                    ]
                ])

                ->add('screenshot', FileType::class, [
                    'label' => 'Ajouter une capture d\'écran du problème si possible',
                    'required' => false,
                    'attr' => [
                        'accept' => 'image/png, image/jpeg, image/jfif'
                    ]
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => HelpCenterFields::class
            ]);
        }
    }