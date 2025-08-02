<?php

    namespace App\Form\Types\Users\Account\Career\ExternalLinks;

    use App\Form\Fields\Users\Account\Career\ExternalLinks\WebsiteUrlEditManagerFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class WebsiteUrlEditManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('websiteUrl', TextType::class, [
                'label' => 'Liens',
                'help' => 'Format: https://www.url.com',
                'required' => false,
                'attr' => [
                    'class' => 'input-counter',
                    'maxlength' => 200
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => WebsiteUrlEditManagerFields::class
            ]);
        }
    }