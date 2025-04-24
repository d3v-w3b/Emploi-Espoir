<?php

    namespace App\Form\Fields\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Validator\Constraints as Assert;

    class ExternalLinkGithubEditManagerFields
    {
        #[Assert\Regex(
            pattern: '/^https:\/\/github\.com\/[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/',
            message: 'Le lien de votre GitHub doit ressembler à ça : https://github.com/username'
        )]
        private ?string $githubUrl = null;


        public function setGithubUrl(?string $githubUrl): void
        {
            $this->githubUrl = $githubUrl;
        }


        public function getGithubUrl(): ?string
        {
            return $this->githubUrl;
        }
    }