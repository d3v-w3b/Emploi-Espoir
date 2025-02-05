<?php

    namespace App\Entity;

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

        #[ORM\ManyToMany(targetEntity: JobOffers::class, inversedBy: 'applicants')]
        private Collection $jobOffer;

        #[ORM\ManyToOne(inversedBy: 'applicants')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;


        public function __construct()
        {
            $this->jobOffer = new ArrayCollection();
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
    }