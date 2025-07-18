<?php

    namespace App\Form\Fields\Users\Profile\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class OrgAccountRemovalRequestFields
    {
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Regex(
            pattern: '#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',
            message: 'L\'email doit être sous la forme: xyz@exemple.com'
        )]
        private ?string $email = null;

        #[Assert\NotBlank]
        private ?string $statu = null;

        #[Assert\Regex(
            pattern: '/^(07|05|01|27|25|21)(\s?\d{2}){4}$/',
            message: 'Le numéro doit être un numéro ivoirien valide (ex : 07 01 02 03 04 ou 2701020304)'
        )]
        private ?string $phone = null;

        #[Assert\NotBlank]
        private ?string $description = null;



        // Setters
        public function setEmail(?string $email): void
        {
            $this->email = $email;
        }

        public function setDescription(?string $description): void
        {
            $this->description = $description;
        }

        public function setPhone(?string $phone): void
        {
            $this->phone = $phone;
        }

        public function setStatu(?string $statu): void
        {
            $this->statu = $statu;
        }



        // Getters
        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }

        public function getStatu(): ?string
        {
            return $this->statu;
        }
    }