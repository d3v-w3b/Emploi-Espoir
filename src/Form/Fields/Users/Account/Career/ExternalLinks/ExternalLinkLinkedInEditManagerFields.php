<?php

    namespace App\Form\Fields\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Validator\Constraints as Assert;

    class ExternalLinkLinkedInEditManagerFields
    {
        #[Assert\Regex(
            pattern: '/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9%_\-]+\/?$/',
            message: 'Le lien de votre profil LinkedIn doit ressembler à ça : https://www.linkedin.com/in/votrenom'
        )]
        private ?string $linkedInUrl = null;


        public function setLinkedInUrl(?string $linkedInUrl): void
        {
            $this->linkedInUrl = $linkedInUrl;
        }

        public function getLinkedInUrl(): ?string
        {
            return $this->linkedInUrl;
        }
    }