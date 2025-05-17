<?php

    namespace App\Form\Fields\Users\Profile\Settings;

    use Symfony\Component\Validator\Constraints as Assert;

    class DeleteUserAccountFields
    {
        #[Assert\NotBlank]
        private ?string $password = null;


        public function setPassword(?string $password): void
        {
            $this->password = $password;
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }
    }