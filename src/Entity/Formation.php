<?php

    namespace App\Entity;

    use App\Enum\User\Account\Career\Formation\DiplomaSpeciality;
    use App\Enum\User\Account\Career\Formation\Months;
    use App\Repository\FormationRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
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

        #[ORM\Column(length: 128, nullable: true, enumType: DiplomaSpeciality::class)]
        private ?DiplomaSpeciality $diplomaSpeciality = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $universityName = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $diplomaTown = null;

        #[ORM\Column(length: 128, nullable: true, enumType: Months::class)]
        private ?Months $diplomaMonth = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $diplomaYear = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $diploma = null;

        #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'formations')]
        private Collection $user;

        public function __construct()
        {
            $this->user = new ArrayCollection();
        }





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

        public function setDiplomaSpeciality(?DiplomaSpeciality $diplomaSpeciality): void
        {
            $this->diplomaSpeciality = $diplomaSpeciality;
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

        public function setDiplomaMonth(?Months $diplomaMonth): void
        {
            $this->diplomaMonth = $diplomaMonth;
        }

        public function setDiplomaYear(?string $diplomaYear): void
        {
            $this->diplomaYear = $diplomaYear;
        }

        public function setDiploma(?array $diploma): static
        {
            $this->diploma = $diploma;

            return $this;
        }

        public function addUser(User $user): static
        {
            if (!$this->user->contains($user)) {
                $this->user->add($user);
            }

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

        public function getDiplomaSpeciality(): ?DiplomaSpeciality
        {
            return $this->diplomaSpeciality;
        }

        public function getUniversityName(): ?string
        {
            return $this->universityName;
        }

        public function getDiplomaTown(): ?string
        {
            return $this->diplomaTown;
        }

        public function getDiplomaMonth(): ?Months
        {
            return $this->diplomaMonth;
        }

        public function getDiplomaYear(): ?string
        {
            return $this->diplomaYear;
        }

        public function getDiploma(): ?array
        {
            return $this->diploma;
        }

        /**
         * @return Collection<int, User>
         */
        public function getUser(): Collection
        {
            return $this->user;
        }


        public function removeUser(User $user): static
        {
            $this->user->removeElement($user);

            return $this;
        }


    }
