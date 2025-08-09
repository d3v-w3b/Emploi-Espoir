<?php

    namespace App\Entity;

    use App\Repository\HiringRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: HiringRepository::class)]
    class Hiring
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $organizationResponse = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $orgOwnerFirstName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $orgOwnerLastName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $orgOwnerEmail = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $orgOwnerPhone = null;

        #[ORM\ManyToOne(inversedBy: 'hirings')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Applicant $applicant = null;

        #[ORM\ManyToOne(inversedBy: 'hirings')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Organization $organization = null;

        

        // Setters and getters

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getOrganizationResponse(): ?string
        {
            return $this->organizationResponse;
        }

        public function setOrganizationResponse(?string $organizationResponse): static
        {
            $this->organizationResponse = $organizationResponse;

            return $this;
        }


        public function getOrgOwnerFirstName(): ?string
        {
            return $this->orgOwnerFirstName;
        }


        public function setOrgOwnerFirstName(?string $orgOwnerFirstName): void
        {
            $this->orgOwnerFirstName = $orgOwnerFirstName;
        }

        public function getOrgOwnerLastName(): ?string
        {
            return $this->orgOwnerLastName;
        }

        public function setOrgOwnerLastName(?string $orgOwnerLastName): static
        {
            $this->orgOwnerLastName = $orgOwnerLastName;

            return $this;
        }

        public function getOrgOwnerEmail(): ?string
        {
            return $this->orgOwnerEmail;
        }

        public function setOrgOwnerEmail(?string $orgOwnerEmail): static
        {
            $this->orgOwnerEmail = $orgOwnerEmail;

            return $this;
        }

        public function getOrgOwnerPhone(): ?string
        {
            return $this->orgOwnerPhone;
        }

        public function setOrgOwnerPhone(?string $orgOwnerPhone): static
        {
            $this->orgOwnerPhone = $orgOwnerPhone;

            return $this;
        }

        public function getApplicant(): ?Applicant
        {
            return $this->applicant;
        }

        public function setApplicant(?Applicant $applicant): static
        {
            $this->applicant = $applicant;

            return $this;
        }

        public function getOrganization(): ?Organization
        {
            return $this->organization;
        }

        public function setOrganization(?Organization $organization): static
        {
            $this->organization = $organization;

            return $this;
        }
    }
