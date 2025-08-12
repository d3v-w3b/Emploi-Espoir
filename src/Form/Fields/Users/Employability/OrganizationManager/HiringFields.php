<?php

    namespace App\Form\Fields\Users\Employability\OrganizationManager;

    use Symfony\Component\Validator\Constraints as Assert;

    class HiringFields
    {
        #[Assert\NotBlank(message: 'Personnalisez votre réponse')]
        private ?string $organizationResponse = null;

        #[Assert\NotBlank(message: 'Renseignez votre prénom')]
        private ?string $orgOwnerFirstName = null;

        #[Assert\NotBlank(message: 'Renseignez votre Nom')]
        private ?string $orgOwnerLastName = null;

        #[Assert\NotBlank(message: 'Renseignez votre email')]
        private ?string $orgOwnerEmail = null;

        #[Assert\Regex(
            pattern: '/^(07|05|01|27|25|21)(\s?\d{2}){4}$/',
            message: 'Le numéro doit être un numéro ivoirien valide (ex : 07 01 02 03 04 ou 2701020304)'
        )]
        private ?string $orgOwnerPhone = null;


        //Setters
        public function setOrgOwnerFirstName(?string $orgOwnerFirstName): void
        {
            $this->orgOwnerFirstName = $orgOwnerFirstName;
        }

        public function setOrganizationResponse(?string $organizationResponse): void
        {
            $this->organizationResponse = $organizationResponse;
        }

        public function setOrgOwnerEmail(?string $orgOwnerEmail): void
        {
            $this->orgOwnerEmail = $orgOwnerEmail;
        }

        public function setOrgOwnerLastName(?string $orgOwnerLastName): void
        {
            $this->orgOwnerLastName = $orgOwnerLastName;
        }

        public function setOrgOwnerPhone(?string $orgOwnerPhone): void
        {
            $this->orgOwnerPhone = $orgOwnerPhone;
        }


        //Getters
        public function getOrgOwnerFirstName(): ?string
        {
            return $this->orgOwnerFirstName;
        }

        public function getOrganizationResponse(): ?string
        {
            return $this->organizationResponse;
        }

        public function getOrgOwnerEmail(): ?string
        {
            return $this->orgOwnerEmail;
        }

        public function getOrgOwnerLastName(): ?string
        {
            return $this->orgOwnerLastName;
        }

        public function getOrgOwnerPhone(): ?string
        {
            return $this->orgOwnerPhone;
        }
    }