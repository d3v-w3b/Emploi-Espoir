<?php

    namespace App\Form\Fields\Users\RegisterAndAuth;

    use Symfony\Component\Validator\Constraints as Assert;

    class EmailFields
    {
        #[Assert\NotBlank]
        #[Assert\Email]
        private ?string $email = null;


        public function setEmail(?string $email): void
        {
            $this->email = $email;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }
    }