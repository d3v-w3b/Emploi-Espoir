<?php

    namespace App\Form\Types\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinkLinkedInEditManagerFields;

    class ExternalLinkLinkedInEditManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('linkedInUrl', TextType::class, [
                'label' => 'Lien de votre profil LinkedIn',
                'help' => 'Format : https://www.linkedin.com/in/votrenom.',
                'required' => false,
                'attr' => [
                    'class' => 'input-counter'
                ]
            ]);
        }



        public function configureOptions(OptionsResolver $resolver):void
        {
            $resolver->setDefaults([
                'data_class' => ExternalLinkLinkedInEditManagerFields::class,
            ]);
        }
    }