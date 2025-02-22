<?php

    namespace App\Entity;

    use App\Repository\CareerRepository;
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
        private ?string $linkedInUrl = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $githubUrl = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $websiteUrl = null;

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


        public function setLinkedInUrl(?string $linkedInUrl): void
        {
            $this->linkedInUrl = $linkedInUrl;
        }

        public function setGithubUrl(?string $githubUrl): void
        {
            $this->githubUrl = $githubUrl;
        }


        public function setWebsiteUrl(?string $websiteUrl): void
        {
            $this->websiteUrl = $websiteUrl;
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


        public function getLinkedInUrl(): ?string
        {
            return $this->linkedInUrl;
        }


        public function getGithubUrl(): ?string
        {
            return $this->githubUrl;
        }


        public function getWebsiteUrl(): ?string
        {
            return $this->websiteUrl;
        }
    }
