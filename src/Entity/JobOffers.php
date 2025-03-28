<?php

    namespace App\Entity;

    use App\Repository\JobOffersRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: JobOffersRepository::class)]
    class JobOffers
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $jobTitle = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $typeOfContract = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $town = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $jobPreferences = null;

        #[ORM\Column(type: 'text', nullable: true)]
        private ?string $organizationAbout = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $missions = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $profilSought = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $whatWeOffer = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $docsToProvide = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $dateOfPublication = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $expirationDate = null;

        #[ORM\Column]
        private bool $statu = true;

        #[ORM\ManyToOne(inversedBy: 'jobOffers')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Organization $organization = null;

        #[ORM\ManyToMany(targetEntity: Applicant::class, mappedBy: 'jobOffer')]
        private Collection $applicants;

        public function __construct()
        {
            $this->applicants = new ArrayCollection();
        }



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

        public function setMissions(?array $missions): void
        {
            $this->missions = $missions;
        }

        public function setProfilSought(?array $profilSought): static
        {
            $this->profilSought = $profilSought;

            return $this;
        }

        public function setWhatWeOffer(?array $whatWeOffer): static
        {
            $this->whatWeOffer = $whatWeOffer;

            return $this;
        }

        public function setDocsToProvide(?array $docsToProvide): static
        {
            $this->docsToProvide = $docsToProvide;

            return $this;
        }

        public function setDateOfPublication(?\DateTimeImmutable $dateOfPublication): static
        {
            $this->dateOfPublication = $dateOfPublication;

            return $this;
        }

        public function setExpirationDate(?\DateTimeImmutable $expirationDate): static
        {
            $this->expirationDate = $expirationDate;

            return $this;
        }


        public function setStatu(bool $statu): void
        {
            $this->statu = $statu;
        }

        public function setOrganization(?Organization $organization): static
        {
            $this->organization = $organization;

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

        public function getMissions(): ?array
        {
            return $this->missions;
        }

        public function getProfilSought(): ?array
        {
            return $this->profilSought;
        }

        public function getWhatWeOffer(): ?array
        {
            return $this->whatWeOffer;
        }

        public function getDocsToProvide(): ?array
        {
            return $this->docsToProvide;
        }

        public function getDateOfPublication(): ?\DateTimeImmutable
        {
            return $this->dateOfPublication;
        }


        public function isStatu(): bool
        {
            return $this->statu;
        }

        public function getExpirationDate(): ?\DateTimeImmutable
        {
            return $this->expirationDate;
        }



        public function getOrganization(): ?Organization
        {
            return $this->organization;
        }

        public function getApplicants(): Collection
        {
            return $this->applicants;
        }

        public function addApplicant(Applicant $applicant): static
        {
            if (!$this->applicants->contains($applicant)) {
                $this->applicants->add($applicant);
                $applicant->addJobOffer($this);
            }

            return $this;
        }

        public function removeApplicant(Applicant $applicant): static
        {
            if ($this->applicants->removeElement($applicant)) {
                $applicant->removeJobOffer($this);
            }

            return $this;
        }
    }
