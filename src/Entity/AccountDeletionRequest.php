<?php

    namespace App\Entity;

    use App\Repository\AccountDeletionRequestRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: AccountDeletionRequestRepository::class)]
    class AccountDeletionRequest
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128)]
        private ?string $email = null;

        #[ORM\Column(length: 128)]
        private ?string $statu = null;

        #[ORM\Column(length: 128)]
        private ?string $telephone = null;

        #[ORM\Column(type: 'text')]
        private ?string $Description = null;

        #[ORM\Column(length: 128)]
        private ?string $applicantEmail = null;

        #[ORM\Column(length: 128)]
        private ?string $applicantOrganization = null;



        //Setters
        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        public function setStatu(string $statu): static
        {
            $this->statu = $statu;

            return $this;
        }

        public function setTelephone(string $telephone): static
        {
            $this->telephone = $telephone;

            return $this;
        }

        public function setDescription(string $Description): static
        {
            $this->Description = $Description;

            return $this;
        }

        public function setApplicantEmail(?string $applicantEmail): void
        {
            $this->applicantEmail = $applicantEmail;
        }

        public function setApplicantOrganization(?string $applicantOrganization): void
        {
            $this->applicantOrganization = $applicantOrganization;
        }



        //Getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getStatu(): ?string
        {
            return $this->statu;
        }

        public function getTelephone(): ?string
        {
            return $this->telephone;
        }

        public function getDescription(): ?string
        {
            return $this->Description;
        }

        public function getApplicantEmail(): ?string
        {
            return $this->applicantEmail;
        }

        public function getApplicantOrganization(): ?string
        {
            return $this->applicantOrganization;
        }
    }
