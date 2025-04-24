<?php

    namespace App\Form\Types\Users\Account\Career\ExternalLinks;

    use App\Form\Fields\Users\Account\Career\ExternalLinks\ExternalLinkGithubEditManagerFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ExternalLinkGithubEditManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('githubUrl', TextType::class, [
                'label' => 'Lien de votre profil Github',
                'help' => 'Format: https://github.com/username',
                'attr' => [
                    'class' => 'input-counter',
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => ExternalLinkGithubEditManagerFields::class,
            ]);
        }
    }