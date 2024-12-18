<?php

    namespace App\Form\Fields\Users\Employability\AddOrganization;

    use Symfony\Component\Validator\Constraints as Assert;

    class OrganizationBasicsInfosFields
    {
        private ?string $country = null;

        #[Assert\NotBlank]
        private ?string $organizationName = null;

        #[Assert\Regex(
            pattern: '/^[A-Z]{2}-\d{9}-[A-Z0-9]$/',
            message: 'Le numéro d\'enregistrement doit respecter le format XX-XXXXXXXXX-Z'
        )]
        #[Assert\NotBlank]
        private ?string $organizationRegistrationNumber = null;


        //setters
        public function setCountry(?string $country): static
        {
            $this->country = $country;

            return $this;
        }

        public function setOrganizationName(?string $organizationName): static
        {
            $this->organizationName = $organizationName;

            return $this;
        }

        public function setOrganizationRegistrationNumber(string $organizationRegistrationNumber): static
        {
            $this->organizationRegistrationNumber = $organizationRegistrationNumber;

            return $this;
        }



        //getters
        public function getCountry(): ?string
        {
            return $this->country;
        }

        public function getOrganizationName(): ?string
        {
            return $this->organizationName;
        }

        public function getOrganizationRegistrationNumber(): ?string
        {
            return $this->organizationRegistrationNumber;
        }
    }
