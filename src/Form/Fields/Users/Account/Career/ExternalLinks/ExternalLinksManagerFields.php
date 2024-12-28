<?php

    namespace App\Form\Fields\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Validator\Constraints as Assert;

    class ExternalLinksManagerFields
    {
        private ?string $linkType = null;

        #[Assert\Regex(
            pattern: '/^https:\/\/www\.linkedin\.com\/in\/[a-zA-Z0-9-]+\/?$/',
            message: 'Le lien de votre profil LinkedIn doit ressembler à ça : https://www.linkedin.com/in/votrenom'
        )]
        private ?string $linkedInLink = null;

        #[Assert\Regex(
            pattern: '/^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
            message: 'Le lien de votre GitHub doit ressembler à ça : https://github.com/username'
        )]
        private ?string $githubLink = null;

        #[Assert\Regex(
            pattern: '/^https?:\/\/(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9._~:\/?#\[\]@!$&\'()*+,;=-]*)?$/',
            message: 'L\'URL doit suivre ce format : https://www.url.com'
        )]
        private ?string $url = null;


        // setters
        public function setGithubLink(?string $githubLink): void
        {
            $this->githubLink = $githubLink;
        }

        public function setLinkedInLink(?string $linkedInLink): void
        {
            $this->linkedInLink = $linkedInLink;
        }

        public function setLinkType(?string $linkType): void
        {
            $this->linkType = $linkType;
        }

        public function setUrl(?string $url): void
        {
            $this->url = $url;
        }



        // getters
        public function getGithubLink(): ?string
        {
            return $this->githubLink;
        }

        public function getLinkedInLink(): ?string
        {
            return $this->linkedInLink;
        }

        public function getLinkType(): ?string
        {
            return $this->linkType;
        }

        public function getUrl(): ?string
        {
            return $this->url;
        }
    }