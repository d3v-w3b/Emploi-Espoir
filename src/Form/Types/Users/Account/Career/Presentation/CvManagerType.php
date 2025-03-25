<?php

    namespace App\Form\Types\Users\Account\Career\Presentation;

    use App\Form\Fields\Users\Account\Career\Presentation\CvManagerFields;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\FileType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class CvManagerType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder->add('cv', FileType::class, [
                'label' => 'CV',
                'help' => 'Fichiers acceptés : .pdf, .doc ou .docx',
                'required' => false
            ]);
        }


        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => CvManagerFields::class
            ]);
        }
    }