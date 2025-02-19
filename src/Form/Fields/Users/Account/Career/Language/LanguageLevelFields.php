<?php

    namespace App\Form\Fields\Users\Account\Career\Language;

    use Symfony\Component\Validator\Constraints as Assert;

    class LanguageLevelFields
    {
        #[Assert\NotBlank]
        private ?string $language = null;

        #[Assert\NotBlank]
        private ?string $languageLevel = null;


        //setters
        public function setLanguage(?string $language): void
        {
            $this->language = $language;
        }

        public function setLanguageLevel(?string $languageLevel): void
        {
            $this->languageLevel = $languageLevel;
        }



        //getters
        public function getLanguage(): ?string
        {
            return $this->language;
        }

        public function getLanguageLevel(): ?string
        {
            return $this->languageLevel;
        }
    }