<?php

    namespace App\Entity;

    use App\Repository\CareerRepository;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: CareerRepository::class)]
    class Career
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $aboutYou = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $cv = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $skills = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $externalLink = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $jobTitle = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $jobField = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $country = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $enterpriseName = null;

        #[ORM\Column(type: 'date_immutable', nullable: true)]
        private ?\DateTimeImmutable $startDate = null;

        #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
        private ?\DateTimeImmutable $endDate = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $jobDescription = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $frenchLevel = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $englishLevel = null;

        #[ORM\OneToOne(inversedBy: 'career', cascade: ['persist', 'remove'])]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        //setters
        public function setAboutYou(?string $aboutYou): static
        {
            $this->aboutYou = $aboutYou;

            return $this;
        }

        public function setCv(?string $cv): static
        {
            $this->cv = $cv;

            return $this;
        }

        public function setSkills(?array $skills): static
        {
            $this->skills = $skills;

            return $this;
        }

        public function setExternalLink(string $externalLink): static
        {
            $this->externalLink = $externalLink;

            return $this;
        }

        public function setJobTitle(?string $jobTitle): static
        {
            $this->jobTitle = $jobTitle;

            return $this;
        }

        public function setJobField(?array $jobField): static
        {
            $this->jobField = $jobField;

            return $this;
        }

        public function setCountry(?string $country): static
        {
            $this->country = $country;

            return $this;
        }

        public function setEnterpriseName(?string $enterpriseName): static
        {
            $this->enterpriseName = $enterpriseName;

            return $this;
        }

        public function setStartDate(?\DateTimeImmutable $startDate): static
        {
            $this->startDate = $startDate;

            return $this;
        }

        public function setEndDate(?\DateTimeImmutable $endDate): static
        {
            $this->endDate = $endDate;

            return $this;
        }

        public function setJobDescription(?string $jobDescription): static
        {
            $this->jobDescription = $jobDescription;

            return $this;
        }

        public function setFrenchLevel(?string $frenchLevel): static
        {
            $this->frenchLevel = $frenchLevel;

            return $this;
        }

        public function setEnglishLevel(?string $englishLevel): static
        {
            $this->englishLevel = $englishLevel;

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

        public function getEnglishLevel(): ?string
        {
            return $this->englishLevel;
        }

        public function getFrenchLevel(): ?string
        {
            return $this->frenchLevel;
        }

        public function getJobDescription(): ?string
        {
            return $this->jobDescription;
        }

        public function getEndDate(): ?\DateTimeImmutable
        {
            return $this->endDate;
        }

        public function getStartDate(): ?\DateTimeImmutable
        {
            return $this->startDate;
        }

        public function getEnterpriseName(): ?string
        {
            return $this->enterpriseName;
        }

        public function getCountry(): ?string
        {
            return $this->country;
        }

        public function getJobField(): ?array
        {
            return $this->jobField;
        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getAboutYou(): ?string
        {
            return $this->aboutYou;
        }

        public function getCv(): ?string
        {
            return $this->cv;
        }

        public function getSkills(): ?array
        {
            return $this->skills;
        }

        public function getExternalLink(): ?string
        {
            return $this->externalLink;
        }

        public function getJobTitle(): ?string
        {
            return $this->jobTitle;
        }
    }
