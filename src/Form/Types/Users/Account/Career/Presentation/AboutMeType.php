<?php

    namespace App\Form\Types\Users\Account\Career\Presentation;

    use App\Form\Fields\Users\Account\Career\Presentation\AboutMeFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class AboutMeType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('aboutMe', TextareaType::class, [
                'label' => 'À propos de vous',
                'help' => 'Cette présentation sera visible sur votre profil',
                'attr' => [
                    'cols' => 80,
                    'rows' => 8,
                    'max_length' => 300,
                ]
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => AboutMeFields::class
            ]);
        }
    }