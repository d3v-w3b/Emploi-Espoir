<?php

    namespace App\Entity;

    use App\Enum\User\Account\Career\Formation\Months;
    use App\Repository\FormationRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: FormationRepository::class)]
    class Formation
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $diplomaLevel = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $diplomaName = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $diplomaSpecialities = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $universityName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $diplomaTown = null;

        #[ORM\Column(type: 'date_immutable', nullable: true, enumType: Months::class)]
        private ?\DateTimeImmutable $diplomaMonth = null;

        #[ORM\Column(type: 'date_immutable', nullable: true)]
        private ?\DateTimeImmutable $diplomaYear = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $diploma = null;

        #[ORM\OneToOne(inversedBy: 'formation', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        //setters
        public function setDiplomaLevel(?string $diplomaLevel): static
        {
            $this->diplomaLevel = $diplomaLevel;

            return $this;
        }

        public function setDiplomaName(?string $diplomaName): static
        {
            $this->diplomaName = $diplomaName;

            return $this;
        }

        public function setDiplomaSpecialities(?array $diplomaSpecialities): static
        {
            $this->diplomaSpecialities = $diplomaSpecialities;

            return $this;
        }

        public function setUniversityName(?string $universityName): static
        {
            $this->universityName = $universityName;

            return $this;
        }

        public function setDiplomaTown(?string $diplomaTown): static
        {
            $this->diplomaTown = $diplomaTown;

            return $this;
        }

        public function setDiplomaMonth(?\DateTimeImmutable $diplomaMonth): static
        {
            $this->diplomaMonth = $diplomaMonth;

            return $this;
        }

        public function setDiplomaYear(?\DateTimeImmutable $diplomaYear): static
        {
            $this->diplomaYear = $diplomaYear;

            return $this;
        }

        public function setDiploma(?array $diploma): static
        {
            $this->diploma = $diploma;

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

        public function getDiplomaLevel(): ?string
        {
            return $this->diplomaLevel;
        }

        public function getDiplomaName(): ?string
        {
            return $this->diplomaName;
        }

        public function getDiplomaSpecialities(): ?array
        {
            return $this->diplomaSpecialities;
        }

        public function getUniversityName(): ?string
        {
            return $this->universityName;
        }

        public function getDiplomaTown(): ?string
        {
            return $this->diplomaTown;
        }

        public function getDiplomaMonth(): ?\DateTimeImmutable
        {
            return $this->diplomaMonth;
        }

        public function getDiplomaYear(): ?\DateTimeImmutable
        {
            return $this->diplomaYear;
        }

        public function getDiploma(): ?array
        {
            return $this->diploma;
        }

        public function getUser(): ?User
        {
            return $this->user;
        }
    }
