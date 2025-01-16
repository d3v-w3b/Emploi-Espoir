<?php

    namespace App\Form\Fields\Users\Employability\OrganizationManager;

    use Symfony\Component\Validator\Constraints as Assert;

    class AddJobOfferFields
    {
        #[Assert\NotBlank]
        #[Assert\Length(
            max: 50,
            maxMessage: 'L\'intitulé de l\'emploi ne doit pas dépasser 50 caractères'
        )]
        private ?string $jobTitle = null;

        #[Assert\NotBlank]
        private ?string $typeOfContract = null;

        #[Assert\NotBlank]
        #[Assert\Length(
            max: 30,
            maxMessage: 'Le nom de la ville ne doit passe dépasser 30 caractères'
        )]
        private ?string $town = null;

        #[Assert\NotBlank]
        private ?string $jobPreferences = null;

        #[Assert\NotBlank]
        private ?string $organizationAbout = null;

        #[Assert\NotBlank]
        private ?array $missions = null;

        #[Assert\NotBlank]
        private ?array $profilSought = null;

        private ?array $whatWeOffer = null;

        #[Assert\NotBlank]
        private ?array $docsToProvide = null;

        #[Assert\NotBlank]
        private ?\DateTimeImmutable $expirationDate = null;



        //setters
        public function setJobTitle(?string $jobTitle): static
        {
            $this->jobTitle = $jobTitle;

            return $this;
        }

        public function setTypeOfContract(?string $typeOfContract): static
        {
            $this->typeOfContract = $typeOfContract;

            return $this;
        }

        public function setTown(?string $town): static
        {
            $this->town = $town;

            return $this;
        }

        public function setJobPreferences(?string $jobPreferences): static
        {
            $this->jobPreferences = $jobPreferences;

            return $this;
        }

        public function setOrganizationAbout(?string $organizationAbout): static
        {
            $this->organizationAbout = $organizationAbout;

            return $this;
        }

        public function setMissions(?array $missions): static
        {
            $this->missions = $missions;

            return $this;
        }

        public function setProfilSought(?array $profilSought): static
        {
            $this->profilSought = $profilSought;

            return $this;
        }

        public function setWhatWeOffer(?array $whatWeOffer): static
        {
            $this->whatWeOffer = $whatWeOffer;

            return $this;
        }

        public function setDocsToProvide(?array $docsToProvide): static
        {
            $this->docsToProvide = $docsToProvide;

            return $this;
        }

        public function setExpirationDate(?\DateTimeImmutable $expirationDate): static
        {
            $this->expirationDate = $expirationDate;

            return $this;
        }



        //getters
        public function getJobTitle(): ?string
        {
            return $this->jobTitle;
        }

        public function getTypeOfContract(): ?string
        {
            return $this->typeOfContract;
        }

        public function getTown(): ?string
        {
            return $this->town;
        }

        public function getJobPreferences(): ?string
        {
            return $this->jobPreferences;
        }

        public function getOrganizationAbout(): ?string
        {
            return $this->organizationAbout;
        }

        public function getMissions(): ?array
        {
            return $this->missions;
        }

        public function getProfilSought(): ?array
        {
            return $this->profilSought;
        }

        public function getWhatWeOffer(): ?array
        {
            return $this->whatWeOffer;
        }

        public function getDocsToProvide(): ?array
        {
            return $this->docsToProvide;
        }

        public function getExpirationDate(): ?\DateTimeImmutable
        {
            return $this->expirationDate;
        }
    }
