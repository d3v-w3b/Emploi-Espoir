<?php

    namespace App\Entity;

    use App\Repository\AdminRepository;
    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Security\Core\User\UserInterface;

    #[ORM\Entity(repositoryClass: AdminRepository::class)]
    #[ORM\Table(name: '`admin`')]
    class Admin implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 180, unique: true)]
        private ?string $email = null;

        #[ORM\Column(length: 128, unique: true)]
        private ?string $adminName = null;

        #[ORM\Column]
        private array $roles = [];

        #[ORM\Column]
        private ?string $password = null;




        // Setters
        public function setEmail(string $email): static
        {
            $this->email = $email;

            return $this;
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

        public function setAdminName(string $adminName): static
        {
            $this->adminName = $adminName;

            return $this;
        }



        // Getters
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        /**
         * A visual identifier that represents this user.
         *
         * @see UserInterface
         */
        public function getUserIdentifier(): string
        {
            return (string) $this->adminName;
        }

        /**
         * @see UserInterface
         *
         * @return list<string>
         */
        public function getRoles(): array
        {
            $roles = $this->roles;
            // guarantee every user at least has ROLE_USER
            $roles[] = 'ROLE_ADMIN';

            return array_unique($roles);
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function getAdminName(): ?string
        {
            return $this->adminName;
        }


        /**
         * @see UserInterface
         */
        public function eraseCredentials(): void
        {
            // If you store any temporary, sensitive data on the user, clear it here
            // $this->plainPassword = null;
        }
    }
