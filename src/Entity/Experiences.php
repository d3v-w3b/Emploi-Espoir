<?php

    namespace App\Entity;

    use App\Repository\ExperiencesRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: ExperiencesRepository::class)]
    class Experiences
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $jobTitle = null;

        #[ORM\Column(nullable: true)]
        private ?array $jobField = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $town = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $enterpriseName = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $startDate = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $endDate = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $jobDescription = null;

        #[ORM\ManyToOne(inversedBy: 'experiences')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        //setters
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


        public function setTown(?string $town): void
        {
            $this->town = $town;
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

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }






        //getters
        public function getId(): ?int
        {
            return $this->id;
        }


        public function getJobTitle(): ?string
        {
            return $this->jobTitle;
        }


        public function getJobField(): ?array
        {
            return $this->jobField;
        }

        public function getTown(): ?string
        {
            return $this->town;
        }


        public function getEnterpriseName(): ?string
        {
            return $this->enterpriseName;
        }


        public function getStartDate(): ?\DateTimeImmutable
        {
            return $this->startDate;
        }


        public function getEndDate(): ?\DateTimeImmutable
        {
            return $this->endDate;
        }


        public function getJobDescription(): ?string
        {
            return $this->jobDescription;
        }


        public function getUser(): ?User
        {
            return $this->user;
        }
    }
