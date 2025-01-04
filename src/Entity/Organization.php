<?php

    namespace App\Entity;

    use App\Repository\OrganizationRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: OrganizationRepository::class)]
    class Organization
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $town = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $organizationName = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $organizationRegistrationNumber = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $numberOfCollaborator = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $organizationPreferences = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $need = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $message = null;

        #[ORM\OneToOne(inversedBy: 'organization', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        //setters
        public function setTown(?string $town): void
        {
            $this->town = $town;
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

        public function setNumberOfCollaborator(?string $numberOfCollaborator): static
        {
            $this->numberOfCollaborator = $numberOfCollaborator;

            return $this;
        }

        public function setOrganizationPreferences(?array $organizationPreferences): void
        {
            $this->organizationPreferences = $organizationPreferences;
        }

        public function setNeed(string $need): static
        {
            $this->need = $need;

            return $this;
        }

        public function setMessage(?string $message): static
        {
            $this->message = $message;

            return $this;
        }

        public function setUser(User $user): static
        {
            $this->user = $user;

            return $this;
        }



        //getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getTown(): ?string
        {
            return $this->town;
        }

        public function getOrganizationName(): ?string
        {
            return $this->organizationName;
        }

        public function getOrganizationRegistrationNumber(): ?string
        {
            return $this->organizationRegistrationNumber;
        }

        public function getNumberOfCollaborator(): ?string
        {
            return $this->numberOfCollaborator;
        }

        public function getOrganizationPreferences(): ?array
        {
            return $this->organizationPreferences;
        }

        public function getNeed(): ?string
        {
            return $this->need;
        }

        public function getMessage(): ?string
        {
            return $this->message;
        }

        public function getUser(): ?User
        {
            return $this->user;
        }
    }
