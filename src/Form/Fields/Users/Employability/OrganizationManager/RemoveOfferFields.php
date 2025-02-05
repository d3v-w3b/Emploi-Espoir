<?php

    namespace App\Form\Fields\Users\Employability\OrganizationManager;

    use Symfony\Component\Validator\Constraints as Assert;

    class RemoveOfferFields
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