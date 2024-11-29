<?php

    namespace App\Entity;

    use App\Repository\JobAndAlternationRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: JobAndAlternationRepository::class)]
    class JobAndAlternation
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $alternationZone = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $alternationPreference = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $alternationField = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $employmentArea = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $employmentPreference = null;

        #[ORM\OneToOne(inversedBy: 'jobAndAlternation', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        //setters
        public function setAlternationZone(?string $alternationZone): static
        {
            $this->alternationZone = $alternationZone;

            return $this;
        }

        public function setAlternationPreference(?array $alternationPreference): static
        {
            $this->alternationPreference = $alternationPreference;

            return $this;
        }

        public function setAlternationField(?string $alternationField): static
        {
            $this->alternationField = $alternationField;

            return $this;
        }

        public function setEmploymentArea(?string $employmentArea): static
        {
            $this->employmentArea = $employmentArea;

            return $this;
        }

        public function setEmploymentPreference(?array $employmentPreference): static
        {
            $this->employmentPreference = $employmentPreference;

            return $this;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }



        //getters
        public function getUser(): ?User
        {
            return $this->user;
        }

        public function getEmploymentPreference(): ?array
        {
            return $this->employmentPreference;
        }

        public function getEmploymentArea(): ?string
        {
            return $this->employmentArea;
        }

        public function getAlternationField(): ?string
        {
            return $this->alternationField;
        }

        public function getAlternationPreference(): ?array
        {
            return $this->alternationPreference;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getAlternationZone(): ?string
        {
            return $this->alternationZone;
        }
    }
