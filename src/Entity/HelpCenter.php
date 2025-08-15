<?php

    namespace App\Entity;

    use App\Repository\HelpCenterRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: HelpCenterRepository::class)]
    class HelpCenter
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $lastName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $firstName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $email = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $phone = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $description = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $screenshot = null;

        #[ORM\ManyToOne(inversedBy: 'helpCenters')]
        #[ORM\JoinColumn(nullable: true)]
        private ?User $user = null;



        // Setters et getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function setLastName(?string $lastName): static
        {
            $this->lastName = $lastName;

            return $this;
        }

        public function getFirstName(): ?string
        {
            return $this->firstName;
        }

        public function setFirstName(?string $firstName): static
        {
            $this->firstName = $firstName;

            return $this;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(?string $email): static
        {
            $this->email = $email;

            return $this;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }

        public function setPhone(?string $phone): static
        {
            $this->phone = $phone;

            return $this;
        }

        public function getDescription(): ?string
        {
            return $this->description;
        }

        public function setDescription(?string $description): static
        {
            $this->description = $description;

            return $this;
        }

        public function getScreenshot(): ?string
        {
            return $this->screenshot;
        }

        public function setScreenshot(?string $screenshot): void
        {
            $this->screenshot = $screenshot;
        }

        public function getUser(): ?User
        {
            return $this->user;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }
    }
