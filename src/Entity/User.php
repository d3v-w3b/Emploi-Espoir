<?php

    namespace App\Entity;

    use App\Repository\UserRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Security\Core\User\UserInterface;

    #[ORM\Entity(repositoryClass: UserRepository::class)]
    #[ORM\Table(name: '`user`')]
    class User implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 180, unique: true)]
        private ?string $email = null;

        #[ORM\Column(length: 180)]
        private ?string $firstName = null;

        #[ORM\Column(length: 180)]
        private ?string $lastName = null;

        #[ORM\Column]
        private ?\DateTimeImmutable $dateOfBirth = null;

        #[ORM\Column]
        private array $roles = [];

        #[ORM\Column]
        private ?string $password = null;

        #[ORM\Column(nullable: true)]
        private ?string $gender = null;

        #[ORM\Column(nullable: true)]
        private ?string $phone = null;

        #[ORM\Column(nullable: true)]
        private ?string $address = null;

        #[ORM\Column(type: 'json', nullable: true)]
        private ?array $mainObjectives = null;

        #[ORM\Column(nullable: true)]
        private ?array $fieldsOfInterest = null;

        #[ORM\Column(nullable: true)]
        private ?string $currentProfessionalSituation = null;

        #[ORM\Column(nullable: true)]
        private ?string $profilPic = null;

        #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
        private ?JobAndAlternation $jobAndAlternation = null;

        #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
        private ?Career $career = null;

        #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
        private ?Formation $formation = null;

        #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
        private ?Organization $organization = null;

        #[ORM\OneToMany(targetEntity: Applicant::class, mappedBy: 'user')]
        private Collection $applicants;

        /**
         * @var Collection<int, Language>
         */
        #[ORM\OneToMany(targetEntity: Language::class, mappedBy: 'user')]
        private Collection $languages;

        /**
         * @var Collection<int, Experiences>
         */
        #[ORM\OneToMany(targetEntity: Experiences::class, mappedBy: 'user')]
        private Collection $experiences;

        public function __construct()
        {
            $this->applicants = new ArrayCollection();
            $this->languages = new ArrayCollection();
            $this->experiences = new ArrayCollection();
        }





        //setters
        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
        }

        public function setFirstName(?string $firstName): void
        {
            $this->firstName = $firstName;
        }

        public function setLastName(?string $lastName): void
        {
            $this->lastName = $lastName;
        }

        public function setDateOfBirth(?\DateTimeImmutable $dateOfBirth): void
        {
            $this->dateOfBirth = $dateOfBirth;
        }

        public function setRoles(array $roles): static
        {
            $this->roles = $roles;

            return $this;
        }

        public function setPassword(string $password): static
        {
            $this->password = $password;

            return $this;
        }

        public function setGender(?string $gender): void
        {
            $this->gender = $gender;
        }

        public function setAddress(?string $address): void
        {
            $this->address = $address;
        }

        public function setCurrentProfessionalSituation(?string $currentProfessionalSituation): void
        {
            $this->currentProfessionalSituation = $currentProfessionalSituation;
        }

        public function setProfilPic(?string $profilPic): void
        {
            $this->profilPic = $profilPic;
        }

        public function setMainObjectives(?array $mainObjectives): void
        {
            $this->mainObjectives = $mainObjectives;
        }

        public function setFieldsOfInterest(?array $fieldsOfInterest): void
        {
            $this->fieldsOfInterest = $fieldsOfInterest;
        }

        public function setPhone(?string $phone): void
        {
            $this->phone = $phone;
        }

        public function setJobAndAlternation(?JobAndAlternation $jobAndAlternation): static
        {
            // unset the owning side of the relation if necessary
            if ($jobAndAlternation === null && $this->jobAndAlternation !== null) {
                $this->jobAndAlternation->setUser(null);
            }

            // set the owning side of the relation if necessary
            if ($jobAndAlternation !== null && $jobAndAlternation->getUser() !== $this) {
                $jobAndAlternation->setUser($this);
            }

            $this->jobAndAlternation = $jobAndAlternation;

            return $this;
        }

        public function setCareer(?Career $career): static
        {
            // unset the owning side of the relation if necessary
            if ($career === null && $this->career !== null) {
                $this->career->setUser(null);
            }

            // set the owning side of the relation if necessary
            if ($career !== null && $career->getUser() !== $this) {
                $career->setUser($this);
            }

            $this->career = $career;

            return $this;
        }

        public function setFormation(Formation $formation): static
        {
            // set the owning side of the relation if necessary
            if ($formation->getUser() !== $this) {
                $formation->setUser($this);
            }

            $this->formation = $formation;

            return $this;
        }

        public function setOrganization(Organization $organization): static
        {
            // set the owning side of the relation if necessary
            if ($organization->getUser() !== $this) {
                $organization->setUser($this);
            }

            $this->organization = $organization;

            return $this;
        }

        public function addApplicant(Applicant $applicant): static
        {
            if (!$this->applicants->contains($applicant)) {
                $this->applicants->add($applicant);
                $applicant->setUser($this);
            }

            return $this;
        }

        public function addLanguage(Language $language): static
        {
            if (!$this->languages->contains($language)) {
                $this->languages->add($language);
                $language->setUser($this);
            }

            return $this;
        }

        public function addExperience(Experiences $experience): static
        {
            if (!$this->experiences->contains($experience)) {
                $this->experiences->add($experience);
                $experience->setUser($this);
            }

            return $this;
        }



        //getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getFirstName(): ?string
        {
            return $this->firstName;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function getDateOfBirth(): ?\DateTimeImmutable
        {
            return $this->dateOfBirth;
        }

        public function getUserIdentifier(): string
        {
            return (string) $this->email;
        }

        public function getRoles(): array
        {
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_USER';

            return array_unique($roles);
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function getAddress(): ?string
        {
            return $this->address;
        }

        public function getCurrentProfessionalSituation(): ?string
        {
            return $this->currentProfessionalSituation;
        }

        public function getProfilPic(): ?string
        {
            return $this->profilPic;
        }

        public function getGender(): ?string
        {
            return $this->gender;
        }

        public function getMainObjectives(): ?array
        {
            return $this->mainObjectives;
        }

        public function getFieldsOfInterest(): ?array
        {
            return $this->fieldsOfInterest;
        }

        public function getPhone(): ?string
        {
            return $this->phone;
        }

        public function eraseCredentials(): void
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
        }

        public function getJobAndAlternation(): ?JobAndAlternation
        {
            return $this->jobAndAlternation;
        }

        public function getCareer(): ?Career
        {
            return $this->career;
        }

        public function getFormation(): ?Formation
        {
            return $this->formation;
        }

        public function getOrganization(): ?Organization
        {
            return $this->organization;
        }


        /**
         * @return Collection<int, Applicant>
         */
        public function getApplicants(): Collection
        {
            return $this->applicants;
        }

        public function removeApplicant(Applicant $applicant): static
        {
            if ($this->applicants->removeElement($applicant)) {
                // set the owning side to null (unless already changed)
                if ($applicant->getUser() === $this) {
                    $applicant->setUser(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, Language>
         */
        public function getLanguages(): Collection
        {
            return $this->languages;
        }

        public function removeLanguage(Language $language): static
        {
            if ($this->languages->removeElement($language)) {
                // set the owning side to null (unless already changed)
                if ($language->getUser() === $this) {
                    $language->setUser(null);
                }
            }

            return $this;
        }

        /**
         * @return Collection<int, Experiences>
         */
        public function getExperiences(): Collection
        {
            return $this->experiences;
        }

        public function removeExperience(Experiences $experience): static
        {
            if ($this->experiences->removeElement($experience)) {
                // set the owning side to null (unless already changed)
                if ($experience->getUser() === $this) {
                    $experience->setUser(null);
                }
            }

            return $this;
        }

    }
