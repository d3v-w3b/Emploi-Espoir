<?php

    namespace App\Form\Fields\Users\Account\PersonalInfos\Identity;

    use Symfony\Component\Validator\Constraints as Assert;

    class LastNameFirstNameEditFields
    {
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 25)]
        private ?string $lastName = null;

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 40)]
        private ?string $firstName = null;


        //setters
        public function setLastName(?string $lastName): void
        {
            $this->lastName = $lastName;
        }

        public function setFirstName(?string $firstName): void
        {
            $this->firstName = $firstName;
        }


        //getters
        public function getFirstName(): ?string
        {
            return $this->firstName;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }
    }