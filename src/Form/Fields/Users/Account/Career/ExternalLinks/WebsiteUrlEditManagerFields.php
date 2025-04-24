<?php

    namespace App\Form\Fields\Users\Account\Career\ExternalLinks;

    use Symfony\Component\Validator\Constraints as Assert;

    class WebsiteUrlEditManagerFields
    {
        #[Assert\Regex(
            pattern: '/^https?:\/\/(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})(\/[a-zA-Z0-9._~:\/?#\[\]@!$&\'()*+,;=-]*)?$/',
            message: 'L\'URL doit suivre ce format : https://www.url.com'
        )]
        private ?string $websiteUrl = null;


        public function setWebsiteUrl(?string $websiteUrl): void
        {
            $this->websiteUrl = $websiteUrl;
        }

        public function getWebsiteUrl(): ?string
        {
            return $this->websiteUrl;
        }
    }