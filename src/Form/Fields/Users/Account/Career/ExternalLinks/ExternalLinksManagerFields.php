<?php

    namespace App\Form\Fields\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Validator\Constraints as Assert;

    class ExternalLinksManagerFields
    {
        private ?string $linkType = null;

        #[Assert\Regex(
            pattern: '/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9%_\-]+\/?$/',
            message: 'Le lien de votre profil LinkedIn doit ressembler à ça : https://www.linkedin.com/in/votrenom'
        )]
        private ?string $linkedInUrl = null;

        #[Assert\Regex(
            pattern: '/^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
            message: 'Le lien de votre GitHub doit ressembler à ça : https://github.com/username'
        )]
        private ?string $githubUrl = null;

        #[Assert\Regex(
            pattern: '/^https?:\/\/(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9._~:\/?#\[\]@!$&\'()*+,;=-]*)?$/',
            message: 'L\'URL doit suivre ce format : https://www.url.com'
        )]
        private ?string $websiteUrl = null;


        // setters
        public function setGithubUrl(?string $githubUrl): void
        {
            $this->githubUrl = $githubUrl;
        }


        public function setLinkedInUrl(?string $linkedInUrl): void
        {
            $this->linkedInUrl = $linkedInUrl;
        }

        public function setLinkType(?string $linkType): void
        {
            $this->linkType = $linkType;
        }


        public function setWebsiteUrl(?string $websiteUrl): void
        {
            $this->websiteUrl = $websiteUrl;
        }



        // getters
        public function getGithubUrl(): ?string
        {
            return $this->githubUrl;
        }


        public function getLinkedInUrl(): ?string
        {
            return $this->linkedInUrl;
        }

        public function getLinkType(): ?string
        {
            return $this->linkType;
        }


        public function getWebsiteUrl(): ?string
        {
            return $this->websiteUrl;
        }
    }