<?php

    namespace App\Form\Fields\Public\HelpCenter;

    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\Validator\Constraints as Assert;

    class HelpCenterFields
    {
        #[Assert\NotBlank]
        private ?string $lastName = null;

        #[Assert\NotBlank]
        private ?string $firstName = null;

        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Regex(
            pattern: '#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',
            message: 'Votre email doit être sous la forme: xyz@exemple.com'
        )]
        private ?string $email = null;

        #[Assert\Regex(
            pattern: '/^(07|05|01|27|25|21)(\s?\d{2}){4}$/',
            message: 'Le numéro doit être un numéro ivoirien valide (ex : 07 01 02 03 04 ou 2701020304)'
        )]
        private ?string $phone = null;

        #[Assert\NotBlank]
        private ?string $description = null;

        #[Assert\File(
            maxSize: '5M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/jfif'],
            mimeTypesMessage: 'Veuillez télécharger une image valide (JPEG, JPG, PNG ou JFIF).'
        )]
        private ?UploadedFile $screenshot = null;


        // Setters
        public function setScreenshot(?UploadedFile $screenshot): void
        {
            $this->screenshot = $screenshot;
        }

        public function setDescription(?string $description): void
        {
            $this->description = $description;
        }

        public function setEmail(?string $email): void
        {
            $this->email = $email;
        }

        public function setFirstName(?string $firstName): void
        {
            $this->firstName = $firstName;
        }

        public function setLastName(?string $lastName): void
        {
            $this->lastName = $lastName;
        }

        public function setPhone(?string $phone): void
        {
            $this->phone = $phone;
        }



        // Getters
        public function getScreenshot(): ?UploadedFile
        {
            return $this->screenshot;
        }


        public function getDescription(): ?string
        {
            return $this->description;
        }


        public function getEmail(): ?string
        {
            return $this->email;
        }


        public function getFirstName(): ?string
        {
            return $this->firstName;
        }


        public function getLastName(): ?string
        {
            return $this->lastName;
        }


        public function getPhone(): ?string
        {
            return $this->phone;
        }
    }