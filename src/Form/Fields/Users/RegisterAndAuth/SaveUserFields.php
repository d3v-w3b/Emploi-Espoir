<?php

    namespace App\Form\Fields\Users\RegisterAndAuth;

    use Symfony\Component\Validator\Constraints as Assert;

    class SaveUserFields
    {
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Regex(
            pattern: '#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',
            message: 'Votre email doit être sous la forme: xyz@exemple.com'
        )]
        private ?string $email = null;

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 25)]
        private ?string $lastName = null;

        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 40)]
        private ?string $firstName = null;

        #[Assert\NotBlank]
        private ?\DateTimeImmutable $dateOfBirth = null;


        //setters
        public function setEmail(?string $email): void
        {
            $this->email = $email;
        }

        public function setDateOfBirth(?\DateTimeImmutable $dateOfBirth): void
        {
            $this->dateOfBirth = $dateOfBirth;
        }

        public function setLastName(?string $lastName): void
        {
            $this->lastName = $lastName;
        }

        public function setFirstName(?string $firstName): void
        {
            $this->firstName = $firstName;
        }



        //getters
        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getDateOfBirth(): ?\DateTimeImmutable
        {
            return $this->dateOfBirth;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function getFirstName(): ?string
        {
            return $this->firstName;
        }
    }