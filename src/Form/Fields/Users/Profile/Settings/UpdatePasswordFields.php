<?php

    namespace App\Form\Fields\Users\Profile\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class UpdatePasswordFields
    {
        #[Assert\NotBlank]
        private ?string $currentPassword = null;

        #[Assert\NotBlank]
        #[Assert\NotCompromisedPassword]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)]
        #[Assert\Regex(
            pattern: '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,32}$/',
            message: 'Le mot de passe doit comporter entre 8 et 32 caractères, avec au moins une majuscule, une minuscule, un chiffre, un caractère spécial, et sans espace.'
        )]
        private ?string $newPassword = null;

        #[Assert\NotBlank]
        private ?string $confirmNewPassword = null;



        //setters
        public function setCurrentPassword(?string $currentPassword): void
        {
            $this->currentPassword = $currentPassword;
        }

        public function setNewPassword(?string $newPassword): void
        {
            $this->newPassword = $newPassword;
        }

        public function setConfirmNewPassword(?string $confirmNewPassword): void
        {
            $this->confirmNewPassword = $confirmNewPassword;
        }



        //getters
        public function getCurrentPassword(): ?string
        {
            return $this->currentPassword;
        }

        public function getNewPassword(): ?string
        {
            return $this->newPassword;
        }

        public function getConfirmNewPassword(): ?string
        {
            return $this->confirmNewPassword;
        }
    }