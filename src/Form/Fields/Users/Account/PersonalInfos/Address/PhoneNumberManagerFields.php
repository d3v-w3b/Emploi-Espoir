<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Address;

    use Symfony\Component\Validator\Constraints as Assert;

    class PhoneNumberManagerFields
    {
        #[Assert\NotBlank]
        #[Assert\Regex(
            pattern: '/^((07|05|01)\d{8})$/',
            message: 'Entrer un numéro de téléphone ivoirien valide'
        )]
        private ?string $phone = null;


        public function setPhone(?string $phone): void
        {
            $this->phone = $phone;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }
    }