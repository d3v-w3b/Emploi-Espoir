<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Address;

    use Symfony\Component\Validator\Constraints as Assert;

    class AddressManagerFields
    {
        #[Assert\NotBlank]
        #[Assert\Length(
            max: 50,
        )]
        private ?string $address = null;


        public function setAddress(?string $address): void
        {
            $this->address = $address;
        }

        public function getAddress(): ?string
        {
            return $this->address;
        }
    }