<?php

    namespace App\Form\Types\Users\Profile\Settings;

    use App\Form\Fields\Users\Profile\Settings\OrgAccountRemovalRequestFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class OrgAccountRemovalRequestType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('email', EmailType::class, [
                    'label' => 'Adresse e-mail',
                ])

                ->add('statu', ChoiceType::class, [
                    'choices' => [
                        '' => '',
                        'Entreprise' => 'Entreprise',
                    ]
                ])

                ->add('phone', TextType::class, [
                    'label' => 'Téléphone'
                ])

                ->add('description', TextareaType::class, [
                    'label' => 'Description',
                    'attr' => [
                        'cols' => 50,
                        'rows' => 4,
                    ]
                ])
            ;
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => OrgAccountRemovalRequestFields::class
            ]);
        }
    }