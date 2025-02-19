<?php

    namespace App\Entity;

    use App\Repository\LanguageRepository;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: LanguageRepository::class)]
    class Language
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $language = null;

        #[ORM\Column(length: 128, nullable: true)]
        private ?string $languageLevel = null;

        #[ORM\ManyToOne(inversedBy: 'languages')]
        #[ORM\JoinColumn(nullable: false)]
        private ?User $user = null;



        // Setters
        public function setLanguage(?string $language): static
        {
            $this->language = $language;

            return $this;
        }

        public function setLanguageLevel(?string $languageLevel): static
        {
            $this->languageLevel = $languageLevel;

            return $this;
        }

        public function setUser(?User $user): static
        {
            $this->user = $user;

            return $this;
        }



        // Getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getLanguage(): ?string
        {
            return $this->language;
        }

        public function getLanguageLevel(): ?string
        {
            return $this->languageLevel;
        }

        public function getUser(): ?User
        {
            return $this->user;
        }
    }
