<?php

    namespace App\Entity;

    use App\Enum\User\Employability\OrganizationManager\ApplicantSource;
    use App\Repository\ApplicantRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: ApplicantRepository::class)]
    class Applicant
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128)]
        private ?string $lastName = null;

        #[ORM\Column(length: 255)]
        private ?string $firstName = null;

        #[ORM\Column(length: 128)]
        private ?string $email = null;

        #[ORM\Column(length: 128)]
        private ?string $phone = null;

        #[ORM\Column(type: 'json')]
        private ?array $docsToProvide = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $offer = null;

        #[ORM\ManyToMany(targetEntity: JobOffers::class, inversedBy: 'applicants')]
        private Collection $jobOffer;

        #[ORM\ManyToOne(inversedBy: 'applicants')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;

        #[ORM\OneToMany(targetEntity: Hiring::class, mappedBy: 'applicant', cascade: ['remove'], orphanRemoval: true)]
        private Collection $hirings;

        #[ORM\Column(length: 128, nullable: true, enumType: ApplicantSource::class)]
        private ?ApplicantSource $source = null;




        public function __construct()
        {
            $this->jobOffer = new ArrayCollection();
            $this->hirings = new ArrayCollection();
        }




        //setters
        public function setLastName(string $lastName): static
        {
            $this->lastName = $lastName;

            return $this;
        }

        public function setFirstName(string $firstName): static
        {
            $this->firstName = $firstName;

            return $this;
        }

        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        public function setPhone(string $phone): static
        {
            $this->phone = $phone;

            return $this;
        }

        public function setDocsToProvide(?array $docsToProvide): void
        {
            $this->docsToProvide = $docsToProvide;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }

        public function setOffer(?string $offer): static
        {
            $this->offer = $offer;

            return $this;
        }

        public function setSource(?ApplicantSource $source): void
        {
            $this->source = $source;
        }





        //getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function getFirstName(): ?string
        {
            return $this->firstName;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }

        public function getDocsToProvide(): ?array
        {
            return $this->docsToProvide;
        }

        public function getJobOffer(): Collection
        {
            return $this->jobOffer;
        }

        public function addJobOffer(JobOffers $jobOffer): static
        {
            if (!$this->jobOffer->contains($jobOffer)) {
                $this->jobOffer->add($jobOffer);
            }

            return $this;
        }

        public function removeJobOffer(JobOffers $jobOffer): static
        {
            $this->jobOffer->removeElement($jobOffer);

            return $this;
        }


        public function getUser(): ?User
        {
            return $this->user;
        }

        public function getOffer(): ?string
        {
            return $this->offer;
        }

        /**
         * @return Collection<int, Hiring>
         */
        public function getHirings(): Collection
        {
            return $this->hirings;
        }

        public function addHiring(Hiring $hiring): static
        {
            if (!$this->hirings->contains($hiring)) {
                $this->hirings->add($hiring);
                $hiring->setApplicant($this);
            }

            return $this;
        }

        public function removeHiring(Hiring $hiring): static
        {
            if ($this->hirings->removeElement($hiring)) {
                // set the owning side to null (unless already changed)
                if ($hiring->getApplicant() === $this) {
                    $hiring->setApplicant(null);
                }
            }

            return $this;
        }

        public function getSource(): ?ApplicantSource
        {
            return $this->source;
        }
    }