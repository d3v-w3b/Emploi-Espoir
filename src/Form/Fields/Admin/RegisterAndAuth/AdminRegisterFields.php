<?php

    namespace App\Form\Fields\Admin\RegisterAndAuth;

    use App\Entity\Admin;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Validator\Constraints as Assert;

    #[UniqueEntity('email', message: 'Cet email semble être déjà utilisé', entityClass: Admin::class)]
    #[UniqueEntity('adminName', message: 'Changez votre nom d\'administration', entityClass: Admin::class)]
    class AdminRegisterFields
    {
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Regex(
            pattern: '#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#',
            message: 'Votre email doit être sous la forme: xyz@exemple.com'
        )]
        private ?string $email = null;

        #[Assert\NotBlank]
        #[Assert\Length(
            min: 3,
            max: 20,
            minMessage: 'Votre nom d\'administrateur soit faire plus de 3 caractères',
            maxMessage: 'Votre nom d\'administrateur ne doit pas dépasser 20 caractères'
        )]
        private ?string $adminName = null;

        #[Assert\NotBlank]
        #[Assert\NotCompromisedPassword]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_STRONG)]
        #[Assert\Regex(
            pattern: '/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])(?=.*\W)(?!.*\s).{8,16}$/',
            message: 'Le mot de passe doit comporter entre 8 et 16 caractères, avec au moins une majuscule, une minuscule, un chiffre, un caractère spécial, et sans espace.'
        )]
        private ?string $password = null;



        // Setters
        public function setEmail(?string $email): void
        {
            $this->email = $email;
        }

        public function setAdminName(?string $adminName): void
        {
            $this->adminName = $adminName;
        }

        public function setPassword(?string $password): void
        {
            $this->password = $password;
        }



        // Getters
        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function getAdminName(): ?string
        {
            return $this->adminName;
        }
    }