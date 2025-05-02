<?php

    namespace App\Form\Fields\Users\Profile\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class UpdateEmailFields
    {
        #[Assert\NotBlank]
        #[Assert\Email]
        private ?string $currentEmail = null;

        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Regex(
            pattern: '#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',
            message: 'Votre email doit être sous la forme: xyz@exemple.com'
        )]
        private ?string $newEmail = null;


        //setters
        public function setCurrentEmail(?string $currentEmail): void
        {
            $this->currentEmail = $currentEmail;
        }

        public function setNewEmail(?string $newEmail): void
        {
            $this->newEmail = $newEmail;
        }


        //getters
        public function getCurrentEmail(): ?string
        {
            return $this->currentEmail;
        }

        public function getNewEmail(): ?string
        {
            return $this->newEmail;
        }
    }