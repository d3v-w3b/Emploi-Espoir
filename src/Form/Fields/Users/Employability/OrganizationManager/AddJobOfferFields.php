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
        private ?string $missions = null;

        #[Assert\NotBlank]
        private ?string $profilSought = null;

        private ?string $whatWeOffer = null;

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

        public function setMissions(?string $missions): void
        {
            $this->missions = $missions;
        }

        public function setProfilSought(?string $profilSought): void
        {
            $this->profilSought = $profilSought;
        }


        public function setWhatWeOffer(?string $whatWeOffer): void
        {
            $this->whatWeOffer = $whatWeOffer;
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


        public function getMissions(): ?string
        {
            return $this->missions;
        }

        public function getProfilSought(): ?string
        {
            return $this->profilSought;
        }

        public function getWhatWeOffer(): ?string
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
