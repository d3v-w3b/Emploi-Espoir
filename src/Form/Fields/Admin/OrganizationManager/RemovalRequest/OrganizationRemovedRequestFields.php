<?php

    namespace App\Form\Fields\Admin\OrganizationManager\RemovalRequest;

    use Symfony\Component\Validator\Constraints as Assert;

    class OrganizationRemovedRequestFields
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